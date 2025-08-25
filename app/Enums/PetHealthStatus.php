<?php

declare(strict_types=1);

namespace App\Enums;

enum PetHealthStatus: string
{
    case Healthy = "healthy";
    case Sick = "sick";
    case Recovering = "recovering";
    case SpecialNeeds = "special_needs";
    case Unknown = "unknown";

    public static function values(): array
    {
        return array_map(fn(PetHealthStatus $e): string => $e->value, self::cases());
    }
}
