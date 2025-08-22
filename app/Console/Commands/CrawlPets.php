<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Integrations\Connectors\CrawlerConnector;
use App\Http\Integrations\Requests\GetPageRequest;
use App\Models\PetShelter;
use App\Services\GeminiService;
use App\Services\PetPageAnalyzer;
use App\Services\PetService;
use App\Services\WebpagePayloadBuilder;
use App\Utils\DomAttributeCleaner;
use App\Utils\DomAttributeExtractor;
use App\Utils\UrlFormatHelper;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Symfony\Component\DomCrawler\Crawler;

class CrawlPets extends Command
{
    protected $signature = "crawl:pets {url?} {--depth=2} {--additional-urls=}";
    protected $description = "Crawl saved url of shelter sites and analyze pages with AI to extract pet info";
    protected PetService $petService;
    protected GeminiService $gemini;
    protected CrawlerConnector $connector;
    protected PetPageAnalyzer $petPageAnalyzer;

    public function handle(
        PetService $petService,
        GeminiService $gemini,
        CrawlerConnector $connector,
        PetPageAnalyzer $petPageAnalyzer,
    ): void {
        $this->petService = $petService;
        $this->gemini = $gemini;
        $this->connector = $connector;
        $this->petPageAnalyzer = $petPageAnalyzer;

        $petSheltersWithExistingUrl = PetShelter::whereNotNull("url")->get();

        if ($argumentUrl = $this->argument("url")) {
            $petShelterUrls = collect([(object)["url" => $argumentUrl, "id" => null]]);
        } else {
            $petShelterUrls = collect($petSheltersWithExistingUrl->toArray());
        }

        if ($additionalUrls = $this->option("additional-urls")) {
            $additionalUrlsArray = collect(explode(",", $additionalUrls))
                ->map(fn($u) => trim($u))
                ->filter(fn($u) => !empty($u))
                ->unique()
                ->all();
            $additionalObjects = array_map(fn($u) => (object)["url" => $u, "id" => null], $additionalUrlsArray);

            $existingUrls = $petShelterUrls->pluck("url")->all();

            foreach ($additionalObjects as $obj) {
                if (!in_array($obj->url, $existingUrls, true)) {
                    $petShelterUrls->push($obj);
                }
            }
        }

        $maxDepth = (int)$this->option("depth");

        foreach ($petShelterUrls as $index => $url) {
            $urlString = is_object($url) ? $url->url : $url["url"];

            $this->info("Processing shelter " . ($index + 1) . " of " . count($petShelterUrls) . ": $urlString");

            $this->crawlSite($urlString, $maxDepth);

            $this->info("Completed crawling: $urlString");
            $this->line("---");

            if ($index < count($petShelterUrls) - 1) {
                sleep(2);
            }
        }
    }

    protected function crawlSite(string $startUrl, int $maxDepth = 2): void
    {
        $queue = [[$startUrl, 0]]; // url + depth
        $visited = [];

        $baseHost = UrlFormatHelper::getUrlHost($startUrl);

        if (!$baseHost) {
            $this->error("Invalid start URL: $startUrl");

            return;
        }

        while ($queue) {
            [$adoptionUrl, $depth] = array_shift($queue);

            if (isset($visited[$adoptionUrl]) || $depth > $maxDepth) {
                continue;
            }

            if ($this->checkIfUrlContainAntiKeywords($adoptionUrl)) {
                $this->info("Contains anti-keywords: $adoptionUrl - Skipping");
                $visited[$adoptionUrl] = true;

                continue;
            }

            $visited[$adoptionUrl] = true;
            $this->info("Crawling (depth $depth): $adoptionUrl");
            Log::info("Crawling page: $adoptionUrl");

            try {
                $response = $this->connector->send(new GetPageRequest($adoptionUrl));

                if ($response->isCached() && $depth > 0) {
                    $this->info("Respone is cached - Skipping HTTP request for $adoptionUrl");

                    continue;
                }
                Log::info("Fetched fresh response for $adoptionUrl");

                $html = $response->body();
            } catch (RequestException $e) {
                Log::warning("HTTP request failed for $adoptionUrl: " . $e->getMessage());

                continue;
            } catch (FatalRequestException $e) {
                Log::error("Critical error while requesting $adoptionUrl: " . $e->getMessage());

                continue;
            }

            if (!$html) {
                $this->warn("Skipping $adoptionUrl due to failed HTTP request or cache.");

                continue;
            }

            $crawler = new Crawler($html);

            $getWebpageClearBodyText = DomAttributeCleaner::clearHtmlUnnecessaryTags($crawler);

            if ((!$this->petPageAnalyzer->isLikelyAPetPage($getWebpageClearBodyText, 7)) && $depth > 0) {
                $this->info("Page does not appear to be specific pet page: $adoptionUrl - Skipping");

                continue;
            }

            DomAttributeCleaner::removeUnnecessaryNodes($crawler);

            $imageAltsArr = DomAttributeExtractor::getImageAltsFromWebpage($crawler);
            $iconsArr = DomAttributeExtractor::getIconsFromWebpage($crawler);
            $svgsArr = DomAttributeExtractor::getSvgLabelsFromWebpage($crawler);

            $imageAlts = array_filter(array_map(
                fn(array $img): string => $img["alt"],
                $imageAltsArr,
            ));

            $iconTitles = array_filter(array_map(
                fn(array $icon): string => $icon["title"],
                $iconsArr,
            ));

            $svgLabels = array_filter(array_map(
                fn(array $svg): string => $svg["aria"],
                $svgsArr,
            ));

            $fullPayload = WebpagePayloadBuilder::build(
                $getWebpageClearBodyText,
                $imageAlts,
                $iconTitles,
                $svgLabels,
            );

            $prompt = config("prompts.crawl_shelters");
            $payload = $this->gemini->createGeminiPayload($prompt, $fullPayload);

            try {
                $result = $this->gemini->generateContent($payload);
                $raw = $this->gemini->getGeminiResult($result);

                if ($raw) {
                    $jsonClean = preg_replace("/^```(json)?|```$/m", "", trim($raw));
                    $this->line($jsonClean);

                    $petData = json_decode($jsonClean, true);

                    if (json_last_error() === JSON_ERROR_NONE && is_array($petData)) {
                        $baseUrl = UrlFormatHelper::getBaseUrl($adoptionUrl);
                        $this->petService->store($petData, $baseUrl, $adoptionUrl);
                    }
                }
                sleep(1);
            } catch (Exception $e) {
                $this->warn("Gemini failed: " . $e->getMessage());
            }

            $links = DomAttributeExtractor::getLinksFromWebpage($crawler);

            foreach ($links as $link) {
                if (!$link) {
                    continue;
                }

                $absoluteUrl = UrlFormatHelper::normalizeUrl($link, $adoptionUrl);

                if (!$absoluteUrl) {
                    continue;
                }

                $linkHost = UrlFormatHelper::getUrlHost($absoluteUrl);

                if ($linkHost !== $baseHost) {
                    continue;
                }

                $queue[] = [$absoluteUrl, $depth + 1];
            }
        }
    }

    protected function checkIfUrlContainAntiKeywords(string $url): bool
    {
        $antiKeywords = config("crawl.anti_keywords");

        return collect($antiKeywords)->contains(fn($keyword) => stripos($url, $keyword) !== false);
    }
}
