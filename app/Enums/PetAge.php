<?php

declare(strict_types=1);

namespace App\Enums;

enum PetAge: string
{
    case Juvenile = "juvenile";
    case Adult = "adult";
    case Senior = "senior";
    case Unknown = "unknown";

    public static function values(): array
    {
        return array_column(self::cases(), "value");
    }
}
