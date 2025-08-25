<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PetPageAnalyzer
{
    public const MINIMAL_SCORE_TRESHOLD = 5;

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

        $lowercasedKeywords = array_map(fn($k): string => mb_strtolower($k), $allKeywords);
        $asciiKeywords = array_map(fn($k): string => Str::ascii(mb_strtolower($k)), $lowercasedKeywords);

        $threshold ??= min(self::MINIMAL_SCORE_TRESHOLD, count($allKeywords));

        $score = 0;
        $matchedKeywords = [];

        foreach ($lowercasedKeywords as $i => $keyword) {
            if ($keyword === "") {
                continue;
            }

            if ((stripos($bodyLower, $keyword) !== false || stripos($bodyAscii, $asciiKeywords[$i]) !== false)
                && !in_array($keyword, $matchedKeywords, true)
            ) {
                $score++;
                $matchedKeywords[] = $keyword;
            }
        }
        Log::info("Threshold: $threshold, Score: $score, Matched keywords: " . implode(", ", $matchedKeywords));

        return $score >= $threshold;
    }
}
