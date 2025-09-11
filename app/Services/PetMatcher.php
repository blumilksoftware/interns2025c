<?php

declare(strict_types=1);

namespace App\Services;

class PetMatcher
{
    public function match(array $petData, array $preferences): float
    {
        $normalized = [];

        foreach ($preferences as $field => $prefValues) {
            if (!is_array($prefValues)) {
                continue;
            }
            $list = array_is_list($prefValues) ? $prefValues : [$prefValues];
            $normalized[$field] = array_values(array_filter($list, static fn($p) => is_array($p) && (array_key_exists("value", $p) || array_key_exists("weight", $p))));
        }

        $score = 0.0;
        $maxScore = 0.0;

        foreach ($normalized as $field => $prefValues) {
            foreach ($prefValues as $pref) {
                $weight = (float)($pref["weight"] ?? 1);
                $maxScore += $weight;

                if ($field === "tags") {
                    $petTags = isset($petData["tags"]) && is_array($petData["tags"]) ? $petData["tags"] : [];
                    $tagValues = array_map(static function ($tag) {
                        if (is_array($tag)) {
                            return $tag["name"] ?? $tag["id"] ?? null;
                        }

                        return $tag;
                    }, $petTags);

                    if (array_key_exists("value", $pref) && in_array($pref["value"], $tagValues, true)) {
                        $score += $weight;
                    }
                } elseif (isset($petData[$field]) && array_key_exists("value", $pref) && $petData[$field] === $pref["value"]) {
                    $score += $weight;
                }
            }
        }

        return $maxScore > 0.0 ? round(($score / $maxScore) * 100, 2) : 0.0;
    }
}
