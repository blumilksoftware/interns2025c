<?php

declare(strict_types=1);

namespace App\Services;

class PetMatcher
{
    public function match(array $pet, array $preferences): float
    {
        $score = 0;
        $maxScore = 0;

        foreach ($preferences as $field => $prefValues) {
            foreach ($prefValues as $pref) {
                $weight = $pref["weight"] ?? 1;
                $maxScore += $weight;

                if ($field === "tags" && isset($pet["tags"])) {
                    if (in_array($pref["value"], $pet["tags"], true)) {
                        $score += $weight;
                    }
                } elseif (isset($pet[$field]) && $pet[$field] === $pref["value"]) {
                    $score += $weight;
                }
            }
        }

        return $maxScore > 0 ? round(($score / $maxScore) * 100, 2) : 0;
    }
    
}
