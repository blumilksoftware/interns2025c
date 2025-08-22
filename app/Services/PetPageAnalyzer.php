<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PetPageAnalyzer
{
    protected array $petKeywords;
    protected array $requiredKeywords;

    public function __construct(?array $petKeywords = null, ?array $requiredKeywords = null)
    {
        $this->petKeywords = $petKeywords ?? config("crawl_keywords.pet_keywords", []);
        $this->requiredKeywords = $requiredKeywords ?? config("crawl_keywords.required_keywords", []);
    }

    public function isLikelyAPetPage(string $body, ?int $threshold = 3): bool
    {
        $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5);
        $bodyLower = mb_strtolower($body);
        $bodyAscii = Str::ascii($bodyLower);

        $allKeywords = array_unique(array_merge($this->petKeywords, $this->requiredKeywords));

        $allKeywordsLower = array_map(fn($k) => mb_strtolower($k), $allKeywords);
        $allKeywordsAscii = array_map(fn($k) => Str::ascii(mb_strtolower($k)), $allKeywordsLower);

        $threshold ??= min(5, count($allKeywords));

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
        Log::info("Threshold: $threshold, Score: $score, Matched keywords: " . implode(", ", $matchedKeywords));

        return $score >= $threshold;
    }
}
