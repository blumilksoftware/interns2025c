<?php

declare(strict_types=1);

namespace App\Enums;

enum PetSpecies: string
{
    case Dog = "dog";
    case Cat = "cat";
    case Other = "other";

    public static function values(): array
    {
        return array_map(fn(PetSpecies $element): string => $element->value, self::cases());
    }
}
