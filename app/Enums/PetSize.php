<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumValues;

enum PetSize: string
{
    use EnumValues;

    case Small = "small";
    case Medium = "medium";
    case Large = "large";
    case Giant = "giant";

    public static function values(): array
    {
        return array_column(self::cases(), "value");
    }
}
