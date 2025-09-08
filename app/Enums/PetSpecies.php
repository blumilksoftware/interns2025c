<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumValues;

enum PetSpecies: string
{
    use EnumValues;

    case Dog = "dog";
    case Cat = "cat";
    case Other = "other";

    public static function values(): array
    {
        return array_column(self::cases(), "value");
    }
}
