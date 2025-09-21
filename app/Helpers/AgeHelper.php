<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Enums\PetAge;

class AgeHelper
{
    public static function parseDbAgeToMonths(string|int|float|null $age): ?int
    {
        if ($age === null) {
            return null;
        }

        if (is_int($age)) {
            return $age >= 0 ? $age : null;
        }

        if (is_float($age)) {
            return $age >= 0 ? (int)floor($age) : null;
        }

        $text = trim((string)$age);

        if ($text === "") {
            return null;
        }

        if (preg_match('/^\d+(?:[.,]\d+)?$/', $text) === 1) {
            return (int)floor((float)str_replace(",", ".", $text));
        }

        $normalized = strtolower($text);
        $hasBelowQualifier = str_contains($normalized, "poniżej");
        $normalized = str_replace(['\t', ","], " ", $normalized);
        $normalized = preg_replace('/\b(około|ok\.|okolo)\b/u', "", $normalized) ?? $normalized;
        $normalized = preg_replace('/\s+/', " ", $normalized) ?? $normalized;

        $yearsVal = 0.0;
        $monthsVal = 0.0;
        $weeksVal = 0.0;

        if (preg_match('/(\d+(?:[.,]\d+)?)\s*(?:rok|roku|lata|lat|r\.)\b/u', $normalized, $m) === 1) {
            $yearsVal = (float)str_replace(",", ".", $m[1]);
        }

        if (preg_match('/(\d+(?:[.,]\d+)?)\s*(?:mies(?:\.|iąc|iąca|iące|ięcy)?|m\.)\b/u', $normalized, $m) === 1) {
            $monthsVal = (float)str_replace(",", ".", $m[1]);
        }

        if (preg_match('/(\d+(?:[.,]\d+)?)\s*(?:tydz(?:\.|ień|odnie|odni)?|t\.)\b/u', $normalized, $m) === 1) {
            $weeksVal = (float)str_replace(",", ".", $m[1]);
        }

        if ($yearsVal === 0.0 && $monthsVal === 0.0 && $weeksVal === 0.0) {
            return null;
        }

        $totalMonths = ($yearsVal * 12.0) + $monthsVal + round($weeksVal / 4.0);
        $totalMonths = (int)floor($totalMonths);

        if ($hasBelowQualifier && $totalMonths > 0) {
            --$totalMonths;
        }

        return max($totalMonths, 0);
    }

    public static function classifyDogAge(?int $months, int $adultFromMonths = 12, int $seniorFromMonths = 96): PetAge
    {
        if ($months === null || $months < 0) {
            return PetAge::Unknown;
        }

        if ($months < $adultFromMonths) {
            return PetAge::Juvenile;
        }

        if ($months >= $seniorFromMonths) {
            return PetAge::Senior;
        }

        return PetAge::Adult;
    }

    public static function classifyDogAgeFromDbAge(string|int|float|null $age, int $adultFromMonths = 12, int $seniorFromMonths = 96): PetAge
    {
        $months = self::parseDbAgeToMonths($age);

        if ($months !== null) {
            return self::classifyDogAge($months, $adultFromMonths, $seniorFromMonths);
        }

        if ($age === null) {
            return PetAge::Unknown;
        }

        $t = mb_strtolower(trim((string)$age));

        if (preg_match("/młod/u", $t) === 1) {
            return PetAge::Juvenile;
        }

        if (preg_match("/senior|starsz/u", $t) === 1) {
            return PetAge::Senior;
        }

        if (preg_match("/doros/u", $t) === 1) {
            return PetAge::Adult;
        }

        return PetAge::Unknown;
    }
}
