<?php

declare(strict_types=1);

namespace App\Enums;

enum PetAttitude: string
{
    case VeryLow = "very low";
    case Low = "low";
    case Medium = "medium";
    case High = "high";
    case VeryHigh = "very high";
    case Unkown = "unknown";

    public static function values(): array
    {
        return array_map(fn(PetAttitude $element): string => $element->value, self::cases());
    }
}
