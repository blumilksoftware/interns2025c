<?php

declare(strict_types=1);

namespace App\Enums;

enum PetAge: string
{
    case Youngling = "youngling";
    case Adult = "adult";
    case Senior = "senior";
    case Unknown = "unknown";

    public static function values(): array
    {
        return array_map(fn(PetAge $element): string => $element->value, self::cases());
    }
}
