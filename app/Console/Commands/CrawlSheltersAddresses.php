<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\PetShelter;
use App\Services\GeminiService;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Log;
use Symfony\Component\DomCrawler\Crawler;

class CrawlSheltersAddresses extends Command
{
    protected $signature = "shelter:crawl {url?} {--batch-size=25 : Number of items per batch}";
    protected $description = "Crawl specific website and analyze content with Gemini AI to save shelters info into DB";

    public function handle(): void
    {
        $client = new Client([
            "timeout" => 120,
            "verify" => false,
        ]);

        $gemini = new GeminiService();
        $batchSize = (int)$this->option("batch-size");

        if ($url = $this->argument("url")) {
            $shelters = collect([(object)["url" => $url, "id" => null]]);
        } else {
            $shelters = PetShelter::all();
        }

        foreach ($shelters as $shelter) {
            $this->info("Checking: $shelter->url");

            try {
                $response = $client->get($shelter->url);
                $html = (string)$response->getBody();
                $crawler = new Crawler($html);

                $this->info("Scrapping: $shelter->url");

                $onlyPetSheltersData = $crawler->filter("p")->each(fn(Crawler $node) => $node->text());

                if (empty($onlyPetSheltersData)) {
                    $this->warn("No Pet Shelter Data content to analyze $shelter->url.");

                    continue;
                }

                // Split the data into batches to avoid hitting Gemini API limits
                $batches = collect($onlyPetSheltersData)->chunk($batchSize);
                $this->info("Splitting data into {$batches->count()} batches of size $batchSize");

                $allResults = [];

                foreach ($batches as $index => $batch) {
                    $this->info("Proccessing batch " . ($index + 1) . " of {$batches->count()}");

                    $batchOfPetShelterData = $batch->implode("\n");

                    $prompt = config("prompts.crawl_shelters_addresses");

                    $batchPrompt = $prompt . "\n\nThis is batch " . ($index + 1) . " z {$batches->count()} 
                    Analyze the following data and return a JSON format";

                    $payload = [
                        "contents" => [
                            [
                                "parts" => [
                                    ["text" => "$batchPrompt \n\n Page content: \n $batchOfPetShelterData]"],
                                ],
                            ],
                        ],
                    ];

                    try {
                        $result = $gemini->generateContent($payload);
                        $raw = $result["candidates"][0]["content"]["parts"][0]["text"] ?? null;

                        if ($raw) {
                            $jsonClean = preg_replace("/^```(json)?|```$/m", "", trim($raw));
                            $analysis = json_decode($jsonClean, true);

                            if (json_last_error() === JSON_ERROR_NONE) {
                                if (is_array($analysis)) {
                                    $allResults[] = $analysis;
                                }
                            } else {
                                $this->line($raw);
                            }
                        }

                        // Short delay between request to API to avoid DDoS protection
                        sleep(1);
                    } catch (Exception $e) {
                        $this->error("Error during Gemini API call for batch " . ($index + 1) . ": " . $e->getMessage());
                        Log::error("Gemini API batch " . ($index + 1) . " failed", [
                            "exception" => $e,
                            "payload" => $payload,
                        ]);

                        continue;
                    }
                }

                // Collect all results from batches
                if (!empty($allResults)) {
                    $finalResult = $this->mergeResults($allResults);

                    Log::info("Gemini analysis for $shelter->url (from {$batches->count()} batches)", $finalResult);

                    $this->info("End result (from {$batches->count()} batches):");
                    $this->line(json_encode($finalResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                    $this->saveSheltersToDatabase($finalResult);
                } else {
                    $this->warn("No valid results");
                }
            } catch (Exception $e) {
                $this->error("Failed before checking $shelter->url due to error: {$e->getMessage()}");
            } catch (GuzzleException $e) {
                Log::error("Cannot fetch URL $shelter->url due to Guzzle error {$e->getMessage()}");
            }
        }

        $this->info("Crawling and analysis completed.");
    }

    private function mergeResults(array $results): array
    {
        $merged = [
            "shelters" => [],
            "total_processed_batches" => count($results),
            "processing_info" => [
                "batched" => true,
                "batch_count" => count($results),
            ],
        ];

        foreach ($results as $result) {
            if (isset($result["shelters"]) && is_array($result["shelters"])) {
                $merged["shelters"] = array_merge($merged["shelters"], $result["shelters"]);
            }
        }

        $merged["total_shelters_found"] = count($merged["shelters"]);

        return $merged;
    }

    private function saveSheltersToDatabase(array $results): void
    {
        if (!isset($results["shelters"])) {
            return;
        }

        foreach ($results["shelters"] as $shelterData) {
            $name = $shelterData["name"] ?? null;
            $phone = $shelterData["phone"] ?? null;

            if (!$name) {
                $this->warn("No name found in Pet Shelter data, skipping");

                continue;
            }

            $shelter = PetShelter::firstOrNew([
                "name" => $name,
                "phone" => $phone,
            ]);
            $shelter->email = $shelterData["email"] ?? $shelter->email;
            $shelter->description = $shelterData["description"] ?? $shelter->description;
            $shelter->url = $shelterData["url"] ?? $shelter->url;

            if ($shelter->exists) {
                if ($shelter->isDirty()) {
                    $shelter->save();
                    $this->line("Updated Pet Shelter: $name");
                } else {
                    $this->line("Skipping: $name");
                }
            } else {
                $shelter->save();
                $this->line("Saving Pet Shelter to DB: $name");
            }

            $addressPayload = [
                "address" => null,
                "city" => null,
                "postal_code" => null,
            ];

            if (isset($shelterData["shelter_address"]) && is_array($shelterData["shelter_address"])) {
                $addressPayload["address"] = $shelterData["shelter_address"]["street"] ?? null;
                $addressPayload["city"] = $shelterData["shelter_address"]["city"] ?? null;
                $addressPayload["postal_code"] = $shelterData["shelter_address"]["postal_code"] ?? null;
            }

            $addressPayload["address"] = $shelterData["address"] ?? $addressPayload["address"];
            $addressPayload["city"] = $shelterData["city"] ?? $addressPayload["city"];
            $addressPayload["postal_code"] = $shelterData["postal_code"] ?? $addressPayload["postal_code"];

            if (array_filter($addressPayload, fn($v) => $v !== null && $v !== "")) {
                $shelter->address()->update($addressPayload);
            }
        }
    }
}
