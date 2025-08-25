<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Helpers\PayloadBuilder;
use App\Helpers\PromptHelper;
use App\Services\GeminiService;
use App\Services\PetShelterService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExtractPetSheltersInfo implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;
    public ?int $backoff = 60;
    public int $timeout = 180;

    public function __construct(
        protected string $batchData,
        protected string $url,
        protected string $baseUrl,
        protected int $batchIndex = 1,
        protected int $totalBatches = 1,
    ) {}

    public function handle(GeminiService $gemini, PetShelterService $petShelterService): void
    {
        $promptKey = PromptHelper::getPromptFromMarkdown("crawl_shelters.md");
        $promptPayload = PayloadBuilder::promptPayload($promptKey);
        $fullPayload = $gemini->createGeminiPayload($promptPayload, $this->batchData);

        try {
            $result = $gemini->generateContent($fullPayload);
            $raw = $gemini->getGeminiResult($result);

            if (!$raw) {
                Log::warning("LLM empty response", [
                    "url" => $this->url,
                    "batchIndex" => $this->batchIndex,
                ]);

                return;
            }

            $jsonClean = preg_replace("/^```(json)?|```$/m", "", trim($raw));
            $analysis = json_decode($jsonClean, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning("JSON decode failed", [
                    "error" => json_last_error_msg(),
                    "url" => $this->url,
                    "batchIndex" => $this->batchIndex,
                ]);

                return;
            }

            if (!is_array($analysis)) {
                Log::warning("LLM returned non-array", [
                    "url" => $this->url,
                    "batchIndex" => $this->batchIndex,
                ]);

                return;
            }

            $petShelterService->store($analysis);

            return;
        } catch (Exception $exception) {
            Log::error("LLM API call failed in job", [
                "exception" => $exception,
                "url" => $this->url,
                "batchIndex" => $this->batchIndex,
            ]);

            throw $exception;
        }
    }

    public function failed(Exception $exception): void
    {
        Log::error("ExtractPetSheltersInfo job failed", [
            "exception" => $exception,
            "url" => $this->url,
            "batchIndex" => $this->batchIndex,
        ]);
    }
}
