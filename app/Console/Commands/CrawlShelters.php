<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Integrations\Connectors\CrawlerConnector;
use App\Http\Integrations\Requests\GetPageRequest;
use App\Jobs\ExtractPetSheltersInfo;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class CrawlShelters extends Command
{
    protected $signature = "crawl:shelters {url} {--batch-size=100 : Number of items per batch}";
    protected $description = "Crawl specific website and analyze content with Gemini AI to save shelters info into DB";
    protected CrawlerConnector $connector;

    public function handle(CrawlerConnector $connector): void
    {
        $this->connector = $connector;

        if (!$this->argument("url")) {
            $this->error("You must provide a URL to crawl.");

            return;
        }

        $batchSize = (int)$this->option("batch-size");
        $url = $this->argument("url");
        $this->info("Checking: $url");

        try {
            $response = $this->connector->send(new GetPageRequest($url));
            $html = $response->body();
            $crawler = new Crawler($html);

            $this->info("Scrapping: $url");

            $onlyPetSheltersData = $crawler
                ->filter("main div, main p")
                ->each(fn(Crawler $node) => trim($node->text()));

            $onlyPetSheltersData = array_values(array_filter($onlyPetSheltersData));

            if (empty($onlyPetSheltersData)) {
                $this->warn("No Pet Shelter Data content to analyze $url.");

                return;
            }

            $batches = collect($onlyPetSheltersData)->chunk($batchSize);
            $this->info("Splitting data into {$batches->count()} batches of size $batchSize");

            $baseUrl = parse_url($url, PHP_URL_HOST) ?: $url;

            foreach ($batches as $index => $batch) {
                $this->info("Dispatching batch " . ($index + 1) . " of {$batches->count()}");

                $batchOfPetShelterData = $batch->implode("\n");

                try {
                    ExtractPetSheltersInfo::dispatch(
                        $batchOfPetShelterData,
                        $url,
                        $baseUrl,
                        $index + 1,
                        $batches->count(),
                    );
                } catch (Exception $e) {
                    $this->error("Failed to dispatch job for batch " . ($index + 1) . ": " . $e->getMessage());
                    Log::error("Dispatch failed", [
                        "exception" => $e,
                        "batch_index" => $index + 1,
                    ]);

                    continue;
                }
            }
        } catch (Exception $e) {
            $this->error("Failed before checking $url due to error: {$e->getMessage()}");
            Log::error("CrawlShelters failed", ["exception" => $e]);
        }

        $this->info("Crawling and dispatching completed.");
    }
}
