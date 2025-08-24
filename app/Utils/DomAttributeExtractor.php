<?php

declare(strict_types=1);

namespace App\Utils;

use Symfony\Component\DomCrawler\Crawler;

class DomAttributeExtractor
{
    /**
     * @return array<int, array{aria: string, class: string}>
     */
    public static function getSvgLabelsFromWebpage(Crawler $crawler): array
    {
        return $crawler->filter("svg[aria-label]")->each(fn(Crawler $node) => [
            "aria" => trim((string)$node->attr("aria-label")),
            "class" => trim($node->attr("class") ?? ""),
        ]);
    }

    /**
     * @return array<int, array{alt: string, class: string, title: string}>
     */
    public static function getImageAltsFromWebpage(Crawler $crawler): array
    {
        return $crawler->filter("img[alt]")->each(fn(Crawler $node) => [
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
            ->each(fn(Crawler $node) => [
                "class" => trim($node->attr("class") ?? ""),
                "title" => trim($node->attr("title") ?? ""),
                "aria" => trim($node->attr("aria-label") ?? ""),
                "data" => trim($node->attr("data-field") ?? ""),
            ]);
    }

    public static function getLinksFromWebpage(Crawler $crawler, string $baseUrl): array
    {
        return collect(
            $crawler->filter("a")->each(fn(Crawler $node) => $node->attr("href")),
        )
            ->filter()
            ->map(fn(string $href) => UrlFormatHelper::normalizeUrl($href, $baseUrl))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
