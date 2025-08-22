<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Integrations\Connectors\CrawlerConnector;
use App\Http\Integrations\Requests\GetPageRequest;
use App\Models\PetShelter;
use App\Services\GeminiService;
use App\Services\PetService;
use App\Utils\UrlFormatHelper;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(
        PetService $petService,
        GeminiService $gemini,
        CrawlerConnector $connector,
    ): void {
        $this->petService = $petService;
        $this->gemini = $gemini;
        $this->connector = $connector;

        $petSheltersWithExistingUrl = PetShelter::whereNotNull("url")->get();

        if ($argumentUrl = $this->argument("url")) {
            $petShelterUrls = collect([(object)["url" => $argumentUrl, "id" => null]]);
        } else {
            $petShelterUrls = collect($petSheltersWithExistingUrl->toArray());
        }

        if ($additionalUrls = $this->option("additional-urls")) {
            $additionalUrlsArray = array_map("trim", explode(",", $additionalUrls));
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

            $getWebpageClearBodyText = $this->clearHtmlUnnecessaryTags($crawler);

            if (!$this->isLikelyAPetPage($getWebpageClearBodyText, 6) && $depth > 0) {
                $this->info("Page does not appear to be specific pet page: $adoptionUrl - Skipping");

                continue;
            }

            $this->removeUnnecessaryNodes($crawler);

            $imageAltsArr = $this->getImageAltsFromWebpage($crawler);
            $iconsArr = $this->getIconsFromWebpage($crawler);
            $svgsArr = $this->getSvgLabelsFromWebpage($crawler);

            $imageAlts = array_filter(array_map(fn($img) => $img["alt"] ?? "", $imageAltsArr));
            $iconTitles = array_filter(array_map(fn($icon) => $icon["title"] ?? "", $iconsArr));
            $svgLabels = array_filter(array_map(fn($svg) => $svg["aria"] ?? "", $svgsArr));

            $fullPayload = $getWebpageClearBodyText .
                "\n\nImage Alts: " . implode(", ", $imageAlts) .
                "\nIcon Titles: " . implode(", ", $iconTitles) .
                "\nSVG Labels: " . implode(", ", $svgLabels);

            $prompt = config("prompts.crawl_shelters");
            $payload = [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => "Prompt: $prompt , Page content: $fullPayload"],
                        ],
                    ],
                ],
            ];

            try {
                $result = $this->gemini->generateContent($payload);
                $raw = $result["candidates"][0]["content"]["parts"][0]["text"] ?? null;

                if ($raw) {
                    $jsonClean = preg_replace("/^```(json)?|```$/m", "", trim($raw));
                    $this->line($jsonClean);

                    $petData = json_decode($jsonClean, true);

                    if (json_last_error() === JSON_ERROR_NONE && is_array($petData)) {
                        $baseUrl = UrlFormatHelper::getBaseUrl($adoptionUrl);
                        $this->petService->store($petData, $baseUrl, $adoptionUrl);
                    } else {
                        $this->warn("Invalid JSON from Gemini for $adoptionUrl");
                    }
                }
                sleep(1);
            } catch (Exception $e) {
                $this->warn("Gemini failed: " . $e->getMessage());
            }

            $links = $crawler->filter("a")->each(fn(Crawler $node) => $node->attr("href"));

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

    protected function removeUnnecessaryNodes(Crawler $crawler): void
    {
        $crawler->filter("script, style, .ads, .sidebar")->each(function (Crawler $node): void {
            $n = $node->getNode(0);

            if ($n && $n->parentNode) {
                $n->parentNode->removeChild($n);
            }
        });
    }

    protected function clearHtmlUnnecessaryTags(Crawler $crawler): string
    {
        $this->removeUnnecessaryNodes($crawler);

        $bodyCrawler = $crawler->filter("body");

        return trim($bodyCrawler->text());
    }

    protected function getSvgLabelsFromWebpage(Crawler $crawler): array
    {
        return $crawler->filter("svg[aria-label]")->each(fn(Crawler $node) => [
            "aria" => trim((string)$node->attr("aria-label")),
            "class" => trim($node->attr("class") ?? ""),
        ]);
    }

    protected function getImageAltsFromWebpage(Crawler $crawler): array
    {
        return $crawler->filter("img[alt]")->each(fn(Crawler $node) => [
            "alt" => trim((string)$node->attr("alt")),
            "class" => trim($node->attr("class") ?? ""),
            "title" => trim($node->attr("title") ?? ""),
        ]);
    }

    protected function getIconsFromWebpage(Crawler $crawler): array
    {
        return $crawler->filter('i[class*="icon-"], span[class*="icon-"], span.check, i.check')
            ->each(fn(Crawler $node) => [
                "class" => trim($node->attr("class") ?? ""),
                "title" => trim($node->attr("title") ?? ""),
                "aria" => trim($node->attr("aria-label") ?? ""),
                "data" => trim($node->attr("data-field") ?? ""),
            ]);
    }

    protected function isLikelyAPetPage(string $body, ?int $threshold = null): bool
    {
        $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5);
        $bodyLower = mb_strtolower($body);
        $bodyAscii = Str::ascii($bodyLower);

        $petKeywords = array_values(array_filter(array_unique(config("crawl_keywords.pet_keywords", []))));
        $requiredKeywords = array_values(array_filter(array_unique(config("crawl_keywords.required_keywords", []))));

        $allKeywords = array_merge($petKeywords, $requiredKeywords);

        $allKeywordsLower = array_map(fn($k) => mb_strtolower($k), $allKeywords);
        $allKeywordsAscii = array_map(fn($k) => Str::ascii(mb_strtolower($k)), $allKeywordsLower);

        if ($threshold === null) {
            $threshold = min(5, count($allKeywords));
        }

        $score = 0;
        $matchedKeywords = [];

        foreach ($allKeywordsLower as $i => $kwLower) {
            if ($kwLower === "") {
                continue;
            }

            if ((stripos($bodyLower, $kwLower) !== false || stripos($bodyAscii, $allKeywordsAscii[$i]) !== false)
                && !in_array($kwLower, $matchedKeywords, true)
            ) {
                $score++;
                $matchedKeywords[] = $kwLower;
            }
        }

        return $score >= $threshold;
    }

    protected function checkIfUrlContainAntiKeywords(string $url): bool
    {
        $antiKeywords = config("crawl.anti_keywords");

        return collect($antiKeywords)->contains(fn($keyword) => stripos($url, $keyword) !== false);
    }
}
