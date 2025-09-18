<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Contracts\LlmConnectorInterface;
use App\Helpers\UrlFormatHelper;
use App\Http\Integrations\Requests\GetPageRequest;
use App\Jobs\ExtractPetSheltersInfo;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class CrawlShelters extends Command
{
    protected $signature = "crawl:shelters {url} {--batch-size=100 : Number of items per batch}";
    protected $description = "Crawl specific website and analyze content with LLM to save shelters info into DB";
    protected LlmConnectorInterface $connector;

    public function handle(LlmConnectorInterface $connector): void
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
                ->each(fn(Crawler $node): string => trim($node->text()));

            $onlyPetSheltersData = array_values(array_filter($onlyPetSheltersData));

            if (empty($onlyPetSheltersData)) {
                $this->warn("No Pet Shelter Data content to analyze $url.");

                return;
            }

            $batches = collect($onlyPetSheltersData)->chunk($batchSize);
            $this->info("Splitting data into {$batches->count()} batches of size $batchSize");

            $baseUrl = UrlFormatHelper::getUrlHost($url) ?: $url;

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
                    )->onQueue("extract_pet_shelters");
                } catch (Exception $exception) {
                    $this->error("Failed to dispatch job for batch " . ($index + 1) . ": " . $exception->getMessage());
                    Log::error("Dispatch failed", [
                        "exception" => $exception,
                        "batch_index" => $index + 1,
                    ]);

                    continue;
                }
            }
        } catch (Exception $exception) {
            $this->error("Failed before checking $url due to error: {$exception->getMessage()}");
            Log::error("CrawlShelters failed", ["exception" => $exception]);
        }

        $this->info("Crawling and dispatching completed.");
    }
}
