<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Helpers\DomAttributeCleaner;
use App\Helpers\DomAttributeExtractor;
use App\Helpers\PayloadBuilder;
use App\Helpers\PromptHelper;
use App\Services\GeminiService;
use App\Services\PetPageAnalyzer;
use App\Services\PetService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use JsonException;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

class AnalyzePetPage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public const int PAGE_SCORE_THRESHOLD = 7;

    public int $tries = 5;
    public int $timeout = 120;
    public ?int $backoff = 180;
    protected PetService $petService;
    protected GeminiService $gemini;
    protected PetPageAnalyzer $petPageAnalyzer;

    public function __construct(
        protected string $html,
        protected string $url,
        protected string $baseUrl,
    ) {}

    public function handle(GeminiService $gemini, PetService $petService, PetPageAnalyzer $petPageAnalyzer): void
    {
        $this->petService = $petService;
        $this->gemini = $gemini;
        $this->petPageAnalyzer = $petPageAnalyzer;

        try {
            $crawler = new Crawler($this->html);
            $cleanText = DomAttributeCleaner::clearHtmlUnnecessaryTags($crawler);

            if (!$this->petPageAnalyzer->isLikelyAPetPage($cleanText, self::PAGE_SCORE_THRESHOLD)) {
                Log::info("Page does not appear to be specific pet page " . $this->url . " - Skipping");

                return;
            }

            DomAttributeCleaner::removeUnnecessaryNodes($crawler);
            $cleanTextWithoutUnnecessaryNodes = $crawler->text();

            $imageAltsArr = DomAttributeExtractor::getImageAltsFromWebpage($crawler);
            $iconsArr = DomAttributeExtractor::getIconsFromWebpage($crawler);
            $svgsArr = DomAttributeExtractor::getSvgLabelsFromWebpage($crawler);

            $imageAlts = array_values(array_filter(array_map(fn($i): ?string => $i["alt"], $imageAltsArr)));
            $iconTitles = array_values(array_filter(array_map(fn($i): ?string => $i["title"], $iconsArr)));
            $svgLabels = array_values(array_filter(array_map(fn($s): ?string => $s["aria"], $svgsArr)));

            $fullPayload = PayloadBuilder::WebpageDomPayload($cleanTextWithoutUnnecessaryNodes, $imageAlts, $iconTitles, $svgLabels);

            $prompt = PromptHelper::getPromptFromMarkdown("crawl_pets.md");

            $payload = $gemini->createGeminiPayload($prompt, $fullPayload);

            $result = $gemini->generateContent($payload);
            $raw = $gemini->getGeminiResult($result);

            if ($raw) {
                $json = $this->extractJsonFromRaw($raw);

                if ($json) {
                    $data = json_decode($json, true);

                    if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                        $petService->store($data, $this->baseUrl, $this->url);
                        Log::info("Pet data extracted and here it is : " . json_encode($data));
                    } else {
                        Log::warning("AI returned invalid JSON for: " . $this->url, ["raw" => $raw]);
                    }
                } else {
                    Log::warning("No JSON block found in AI response for: " . $this->url, ["raw" => $raw]);
                }
            }
        } catch (JsonException $exception) {
            Log::error("JSON decoding error in AnalyzePetPage job for URL: " . $this->url, ["error" => $exception->getMessage()]);
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::error("AnalyzePetPage permanently failed", [
            "url" => $this->url,
            "message" => $exception->getMessage(),
        ]);
    }

    public function backoff(): int
    {
        return 3;
    }

    protected function extractJsonFromRaw(string $raw): ?string
    {
        $start = strpos($raw, "{");
        $end = strrpos($raw, "}");

        if ($start === false || $end === false || $end < $start) {
            return null;
        }

        return substr($raw, $start, $end - $start + 1);
    }
}
