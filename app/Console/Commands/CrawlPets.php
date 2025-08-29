<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Contracts\LlmConnectorInterface;
use App\Helpers\DomAttributeExtractor;
use App\Helpers\PetShelterHelper;
use App\Helpers\UrlFormatHelper;
use App\Helpers\UrlValidator;
use App\Http\Integrations\Requests\GetPageRequest;
use App\Jobs\AnalyzePetPage;
use App\Services\GeminiService;
use App\Services\PetPageAnalyzer;
use App\Services\PetShelterService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Saloon\Exceptions\Request\FatalRequestException;
use Symfony\Component\DomCrawler\Crawler;

class CrawlPets extends Command
{
    protected $signature = "crawl:pets {url?} {--depth=2} {--additional-urls=}";
    protected $description = "Crawl saved url of shelter sites and analyze pages with AI to extract pet info";
    protected PetShelterService $petShelterService;
    protected GeminiService $gemini;
    protected LlmConnectorInterface $connector;
    protected PetPageAnalyzer $petPageAnalyzer;

    public function handle(
        GeminiService $gemini,
        LlmConnectorInterface $connector,
        PetPageAnalyzer $petPageAnalyzer,
    ): void {
        $this->gemini = $gemini;
        $this->connector = $connector;
        $this->petPageAnalyzer = $petPageAnalyzer;

        $petSheltersWithExistingUrl = PetShelterHelper::getAllPetShelterUrls();

        if ($argumentUrl = $this->argument("url")) {
            $petShelterUrls = collect([$argumentUrl]);
        } else {
            $petShelterUrls = collect($petSheltersWithExistingUrl);
        }

        if ($additionalUrls = $this->option("additional-urls")) {
            $additionalUrlsArray = collect(explode(",", $additionalUrls))
                ->map(fn(string $url): string => trim($url))
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
        $urlsToVisit = [[$startUrl, 0]]; // url + depth
        $visited = [];

        $baseHost = UrlFormatHelper::getUrlHost($startUrl);

        if (!$baseHost) {
            $this->error("Invalid start URL: $startUrl");

            return;
        }

        while ($urlsToVisit) {
            [$currentUrl, $depth] = array_shift($urlsToVisit);

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
            } catch (FatalRequestException $exception) {
                $this->warn("Failed to fetch $currentUrl: " . $exception->getMessage());

                continue;
            }

            if ($response->isCached() && $depth > 0) {
                $this->info("Response is cached - Skipping HTTP request for $currentUrl");

                continue;
            }
            Log::info("Fetched fresh response for $currentUrl");

            $html = $response->body();

            if (!$html) {
                $this->warn("Skipping $currentUrl due to failed HTTP request or cache.");

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

                $urlsToVisit[] = [$link, $depth + 1];
            }
        }
    }
}
