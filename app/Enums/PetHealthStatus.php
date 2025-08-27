<?php

declare(strict_types=1);

namespace App\Enums;

enum PetHealthStatus: string
{
    case Healthy = "healthy";
    case Sick = "sick";
    case Recovering = "recovering";
    case Critical = "critical";
    case Unknown = "unknown";

    public static function values(): array
    {
        return array_map(fn(PetHealthStatus $element): string => $element->value, self::cases());
    }
}
