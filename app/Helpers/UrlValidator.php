<?php

declare(strict_types=1);

namespace App\Helpers;

class UrlValidator
{
    public static function checkIfUrlContainAntiKeywords(string $url, string $config_key): bool
    {
        $antiKeywords = config($config_key, []);

        return collect($antiKeywords)->contains(fn(string $keyword): bool => stripos($url, $keyword) !== false);
    }
}
