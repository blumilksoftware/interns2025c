<?php

declare(strict_types=1);

namespace App\Enums;

enum PetAge: string
{
    case Puppy = "puppy";
    case Adult = "adult";
    case Senior = "senior";
    case Unknown = "unknown";

    public static function values(): array
    {
        return array_map(fn($e) => $e->value, self::cases());
    }
}
