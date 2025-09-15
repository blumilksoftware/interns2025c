<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tag;
use Illuminate\Support\Str;

class TagService
{
    public static function extractTagsFromText(string $text): array
    {
        $service = new self();

        $text = trim($text);

        if ($text === "") {
            return [];
        }

        $rawTokens = preg_split('/\s+/u', $text) ?: [];

        $unique = [];

        foreach ($rawTokens as $token) {
            $clean = $service->sanitizeTagName($token);

            if ($clean !== null) {
                $unique[$clean] = true;
            }
        }

        return array_keys($unique);
    }

    public function processTagsAndGetIds(array $tags): array
    {
        return collect($tags)
            ->map(fn(string|array $tag): ?int => $this->getTagId($tag))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function sanitizeTagName(string $tagName): ?string
    {
        $sanitized = Str::squish($tagName);
        $sanitized = Str::replaceMatches('/[^\p{L}\s]/u', "", $sanitized);
        $sanitized = Str::squish($sanitized);
        $sanitized = Str::lower($sanitized);

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
