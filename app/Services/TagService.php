<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tag;

class TagService
{
    public function processTagsAndGetIds(array $tags): array
    {
        $tagIds = [];

        foreach ($tags as $tag) {
            if (is_array($tag) && isset($tag["id"])) {
                $tagIds[] = $tag["id"];
            } 
            // If tag is just a name string (new tag)
            elseif (is_string($tag)) {
                $sanitizedTagName = $this->sanitizeSingleTagName($tag);

                if ($sanitizedTagName) {
                    $tagModel = Tag::query()->firstOrCreate(["name" => $sanitizedTagName]);
                    $tagIds[] = $tagModel->id;
                }
            }
            // If tag is an array with name but no ID (new tag from frontend)
            elseif (is_array($tag) && isset($tag["name"]) && !isset($tag["id"])) {
                $sanitizedTagName = $this->sanitizeSingleTagName($tag["name"]);

                if ($sanitizedTagName) {
                    $tagModel = Tag::query()->firstOrCreate(["name" => $sanitizedTagName]);
                    $tagIds[] = $tagModel->id;
                }
            }
        }

        return array_unique($tagIds);
    }    

    public function sanitizeTagName(string|array $tagNames): string|array|null
    {
        if (is_string($tagNames)) {
            return $this->sanitizeSingleTagName($tagNames);
        }

        if (is_array($tagNames)) {
            return $this->sanitizeTagNamesArray($tagNames);
        }

        return null;
    }

    private function sanitizeSingleTagName(string $tagName): ?string
    {
        $sanitized = trim(preg_replace('/\s+/', " ", $tagName));
        $sanitized = preg_replace('/[^\p{L}\s]/u', "", $sanitized);
        $sanitized = trim(preg_replace('/\s+/', " ", $sanitized));

        return $sanitized !== "" ? $sanitized : null;
    }

    private function sanitizeTagNamesArray(array $tagNames): array
    {
        $sanitizedTags = [];

        foreach ($tagNames as $tagName) {
            if (!is_string($tagName)) {
                continue;
            }

            $sanitized = $this->sanitizeSingleTagName($tagName);

            if ($sanitized !== null) {
                $sanitizedTags[] = $sanitized;
            }
        }

        return array_unique($sanitizedTags);
    }
}
