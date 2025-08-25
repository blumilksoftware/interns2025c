<?php

declare(strict_types=1);

namespace App\Enums;

enum PetSex: string
{
    case Male = "male";
    case Female = "female";
    case Unknown = "unknown";

    public static function values(): array
    {
        return array_map(fn(PetSex $e): string => $e->value, self::cases());
    }
}
