<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tag;

class TagService
{
    public function processTagsAndGetIds(array $tags): array
    {
        return collect($tags)
            ->map(fn($tag) => $this->getTagId($tag))
            ->filter()      
            ->unique()       
            ->values()
            ->all();
    }

    public function sanitizeTagName(string $tagName): ?string
    {
        $sanitized = trim(preg_replace('/\s+/', " ", $tagName));
        $sanitized = preg_replace('/[^\p{L}\s]/u', "", $sanitized);
        $sanitized = trim(preg_replace('/\s+/', " ", $sanitized));

        return $sanitized !== "" ? $sanitized : null;
    }

    private function getTagId(string|array $tag): ?int
    {
        if (is_array($tag) && !empty($tag["id"])) {
            return $tag["id"];
        }

        $tagName = match(true) {
            is_string($tag) => $tag,
            is_array($tag) && !empty($tag["name"]) => $tag["name"],
            default => null,
        };

        if (!$tagName) {
            return null;
        }

        $sanitized = $this->sanitizeTagName($tagName);

        if (!$sanitized) {
            return null;
        }

        return $this->getOrCreateTagId($sanitized);
    }

    private function getOrCreateTagId(string $sanitizedName): int
    {
        $tagModel = Tag::firstOrCreate(["name" => $sanitizedName]);

        return $tagModel->id;
    }
}
