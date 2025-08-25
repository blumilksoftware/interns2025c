<?php

declare(strict_types=1);

namespace App\Helpers;

use Symfony\Component\DomCrawler\Crawler;

class DomAttributeCleaner
{
    public static function removeUnnecessaryNodes(Crawler $crawler): void
    {
        $crawler->filter("script, style, .ads, .sidebar")->each(function (Crawler $node): void {
            $n = $node->getNode(0);

            if ($n && $n->parentNode) {
                $n->parentNode->removeChild($n);
            }
        });
    }

    public static function clearHtmlUnnecessaryTags(Crawler $crawler): string
    {
        $bodyCrawler = $crawler->filter("body");

        return trim($bodyCrawler->text());
    }
}
