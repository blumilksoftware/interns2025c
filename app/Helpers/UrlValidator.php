<?php

declare(strict_types=1);

namespace App\Helpers;

class UrlValidator
{
    public static function checkIfUrlContainAntiKeywords(string $url, string $configKey): bool
    {
        $antiKeywords = config($configKey, []);

        return collect($antiKeywords)->contains(fn(string $keyword): bool => str_contains($url, $keyword) !== false);
    }
}
