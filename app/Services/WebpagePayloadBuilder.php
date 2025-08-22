<?php

declare(strict_types=1);

namespace App\Services;

class WebpagePayloadBuilder
{
    public static function build(string $cleanText, array $imageAlts, array $iconTitles, array $svgLabels): string
    {
        return $cleanText .
            "\n\nImage Alts: " . implode(", ", $imageAlts) .
            "\nIcon Titles: " . implode(", ", $iconTitles) .
            "\nSVG Labels: " . implode(", ", $svgLabels);
    }
}
