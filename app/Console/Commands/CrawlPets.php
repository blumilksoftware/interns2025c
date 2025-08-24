<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Integrations\Connectors\CrawlerConnector;
use App\Http\Integrations\Requests\GetPageRequest;
use App\Jobs\AnalyzePetPage;
use App\Models\PetShelter;
use App\Services\GeminiService;
use App\Services\PetPageAnalyzer;
use App\Services\PetShelterService;
use App\Utils\DomAttributeExtractor;
use App\Utils\UrlFormatHelper;
use App\Utils\UrlValidator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Symfony\Component\DomCrawler\Crawler;

class CrawlPets extends Command
{
    protected $signature = "crawl:pets {url?} {--depth=2} {--additional-urls=}";
    protected $description = "Crawl saved url of shelter sites and analyze pages with AI to extract pet info";
    protected PetShelterService $petShelterService;
    protected GeminiService $gemini;
    protected CrawlerConnector $connector;
    protected PetPageAnalyzer $petPageAnalyzer;

    public function handle(
        GeminiService $gemini,
        CrawlerConnector $connector,
        PetPageAnalyzer $petPageAnalyzer,
    ): void {
        $this->gemini = $gemini;
        $this->connector = $connector;
        $this->petPageAnalyzer = $petPageAnalyzer;

        $petSheltersWithExistingUrl = PetShelter::getAllPetShelterUrls();

        if ($argumentUrl = $this->argument("url")) {
            $petShelterUrls = collect([$argumentUrl]);
        } else {
            $petShelterUrls = collect($petSheltersWithExistingUrl);
        }

        if ($additionalUrls = $this->option("additional-urls")) {
            $additionalUrlsArray = collect(explode(",", $additionalUrls))
                ->map(fn($u) => trim($u))
                ->filter()
                ->unique()
                ->diff($petShelterUrls);

            $petShelterUrls = $petShelterUrls->merge($additionalUrlsArray);
        }

        $maxDepth = (int)$this->option("depth");

        if ($maxDepth < 1) {
            $this->error("Invalid depth value. It must be a positive integer.");

            return;
        }

        foreach ($petShelterUrls as $index => $urlString) {
            $this->info("Processing shelter " . ($index + 1) . " of " . count($petShelterUrls) . ": $urlString");
            $this->crawlSite($urlString, $maxDepth);
            $this->info("Completed crawling: $urlString");
            $this->line("---");
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
            [$currentUrl, $depth] = array_shift($queue);

            if (isset($visited[$currentUrl]) || $depth > $maxDepth) {
                continue;
            }

            if (UrlValidator::checkIfUrlContainAntiKeywords($currentUrl, "crawl_keywords.anti_keywords") && $depth > 0) {
                $this->info("Contains anti-keywords: $currentUrl - Skipping");
                $visited[$currentUrl] = true;

                continue;
            }

            $visited[$currentUrl] = true;
            $this->info("Crawling (depth $depth): $currentUrl");
            Log::info("Crawling page: $currentUrl");

            try {
                $response = $this->connector->send(new GetPageRequest($currentUrl));

                if ($response->isCached() && $depth > 0) {
                    $this->info("Respone is cached - Skipping HTTP request for $currentUrl");

                    continue;
                }
                Log::info("Fetched fresh response for $currentUrl");

                $html = $response->body();

                if (!$html) {
                    $this->warn("Skipping $currentUrl due to failed HTTP request or cache.");

                    continue;
                }
            } catch (RequestException $e) {
                Log::warning("HTTP request failed for $currentUrl: " . $e->getMessage());

                continue;
            } catch (FatalRequestException $e) {
                Log::error("Critical error while requesting $currentUrl: " . $e->getMessage());

                continue;
            }

            AnalyzePetPage::dispatch($html, $currentUrl, UrlFormatHelper::getBaseUrl($currentUrl))
                ->onQueue("analyze_pet_pages");

            $crawler = new Crawler($html);
            $links = DomAttributeExtractor::getLinksFromWebpage($crawler, $currentUrl);

            foreach ($links as $link) {
                $linkHost = UrlFormatHelper::getUrlHost($link);

                if ($linkHost !== $baseHost) {
                    continue;
                }

                $queue[] = [$link, $depth + 1];
            }
        }
    }
}
