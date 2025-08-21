<?php

declare(strict_types=1);

namespace App\Utils;

use Illuminate\Support\Str;

class UrlFormatHelper
{
    public static function getBaseUrl(string $url): string
    {
        $parts = parse_url($url);

        $scheme = $parts["scheme"] ?? "http";
        $host = $parts["host"] ?? "";
        $port = isset($parts["port"]) ? ":" . $parts["port"] : "";

        return "{$scheme}://{$host}{$port}/";
    }

    public static function getUrlHost(string $url): ?string
    {
        $parts = parse_url($url);

        return $parts["host"] ?? null;
    }

    public static function normalizeUrl(string $url, string $baseUrl): ?string
    {
        if (Str::startsWith($url, ["#", "javascript:", "mailto:"])) {
            return null;
        }

        if (Str::startsWith($url, ["http://", "https://"])) {
            return $url;
        }

        $baseParts = parse_url($baseUrl);
        $scheme = $baseParts["scheme"] ?? "https";
        $host = $baseParts["host"] ?? "";

        return $scheme . "://" . $host . "/" . ltrim($url, "/");
    }
}
