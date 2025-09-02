<?php

declare(strict_types=1);

namespace App\Services;

class PetMatcher
{
    public function match(array $petData, array $preferences): float
    {
        $score = 0;
        $maxScore = 0;

        foreach ($preferences as $field => $prefValues) {
            foreach ($prefValues as $pref) {
                $weight = $pref["weight"] ?? 1;
                $maxScore += $weight;

                if ($field === "tags" && isset($petData["tags"])) {
                    $tagValues = array_map(fn($tag) => $tag["name"] ?? $tag["id"], $petData["tags"]);

                    if (in_array($pref["value"], $tagValues, true)) {
                        $score += $weight;
                    }
                } elseif (isset($petData[$field]) && $petData[$field] === $pref["value"]) {
                    $score += $weight;
                }
            }
        }

        return $maxScore > 0 ? round(($score / $maxScore) * 100, 2) : 0;
    }
}
