<?php

declare(strict_types=1);

namespace App\Helpers;

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

    public static function getPathInfoExtension(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);

        if ($path === null) {
            return null;
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return $extension !== "" ? strtolower($extension) : null;
    }

    public static function sanitizeUtf8(string $input): string
    {
        $detected = @mb_detect_encoding($input, ["UTF-8", "ISO-8859-2", "ISO-8859-1", "ASCII"], true);

        if ($detected && $detected !== "UTF-8") {
            $input = @mb_convert_encoding($input, "UTF-8", $detected);
        }

        $converted = @iconv("UTF-8", "UTF-8//IGNORE", $input);

        if ($converted !== false) {
            $input = $converted;
        }

        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', "", $input) ?? $input;

        return $input;
    }
}
