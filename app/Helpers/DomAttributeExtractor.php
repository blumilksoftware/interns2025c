<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

class DomAttributeExtractor
{
    /**
     * @return array<int, array{aria: string, class: string}>
     */
    public static function getSvgLabelsFromWebpage(Crawler $crawler): array
    {
        return $crawler->filter("svg[aria-label]")->each(fn(Crawler $node): array => [
            "aria" => trim((string)$node->attr("aria-label")),
            "class" => trim($node->attr("class") ?? ""),
        ]);
    }

    /**
     * @return array<int, array{alt: string, class: string, title: string}>
     */
    public static function getImageAltsFromWebpage(Crawler $crawler): array
    {
        return $crawler->filter("img[alt]")->each(fn(Crawler $node): array => [
            "alt" => trim((string)$node->attr("alt")),
            "class" => trim($node->attr("class") ?? ""),
            "title" => trim($node->attr("title") ?? ""),
        ]);
    }

    /**
     * @return array<int, array{class: string, title: string, aria: string, data: string}>
     */
    public static function getIconsFromWebpage(Crawler $crawler): array
    {
        return $crawler->filter('i[class*="icon-"], span[class*="icon-"], span.check, i.check')
            ->each(fn(Crawler $node): array => [
                "class" => trim($node->attr("class") ?? ""),
                "title" => trim($node->attr("title") ?? ""),
                "aria" => trim($node->attr("aria-label") ?? ""),
                "data" => trim($node->attr("data-field") ?? ""),
            ]);
    }

    public static function getLinksFromWebpage(Crawler $crawler, string $baseUrl): array
    {
        return collect(
            $crawler->filter("a")->each(fn(Crawler $node): ?string => $node->attr("href")),
        )
            ->filter()
            ->map(fn(string $href): ?string => UrlFormatHelper::normalizeUrl($href, $baseUrl))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public static function scrapImageLinksFromWebpage(Crawler $crawler, string $baseUrl): array
    {
        $images = $crawler->filter("img")->each(function (Crawler $node) use ($baseUrl): ?string {
            $allowedExtensions = ["jpg", "jpeg", "png"];
            $src = (string)$node->attr("src");

            if (!$src) {
                return null;
            }

            $extension = UrlFormatHelper::getPathInfoExtension($src);

            if (!in_array($extension, $allowedExtensions, true)) {
                return null;
            }

            $normalizedUrl = UrlFormatHelper::normalizeUrl($src, $baseUrl);

            if (!$normalizedUrl) {
                return null;
            }

            return $normalizedUrl;
        });

        return array_filter($images);
    }

    public static function DoesImageMeetMinimumDimensions(string $imageUrl, int $minWidth, int $minHeight): bool
    {
        try {
            $imageInfo = @getimagesize($imageUrl);

            if ($imageInfo === false) {
                Log::debug("Could not get image dimensions for: {$imageUrl}");

                return false;
            }

            [$width, $height] = $imageInfo;

            $meetsRequirements = $width >= $minWidth && $height >= $minHeight;

            if (!$meetsRequirements) {
                Log::debug("Image {$imageUrl} does not meet minimum dimensions ({$width}x{$height} < " . $minWidth . "x" . $minHeight . ")");
            }

            return $meetsRequirements;
        } catch (Throwable $exception) {
            Log::debug("Error checking image dimensions for {$imageUrl}: " . $exception->getMessage());

            return false;
        }
    }
}
