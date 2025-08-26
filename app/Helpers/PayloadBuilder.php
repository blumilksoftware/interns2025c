<?php

declare(strict_types=1);

namespace App\Helpers;

class PayloadBuilder
{
    public static function WebpageDomPayload(
        string $cleanText,
        array $imageAlts,
        array $iconTitles,
        array $svgLabels,
        ?string $additional = null,
    ): string {
        return $cleanText .
            "\n\nImage Alts: " . implode(", ", $imageAlts) .
            "\nIcon Titles: " . implode(", ", $iconTitles) .
            "\nSVG Labels: " . implode(", ", $svgLabels) .
            ($additional ? "\nAdditional Info: " . $additional : "");
    }

    public static function promptPayload(string $content, ?string $additional = null): string
    {
        return $content . ($additional ? "\nAdditional Info: " . $additional : "");
    }
}
