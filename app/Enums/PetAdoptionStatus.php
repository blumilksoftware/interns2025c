<?php

declare(strict_types=1);

namespace App\Enums;

enum PetAdoptionStatus: string
{
    case Adopted = "adopted";
    case WaitingForAdoption = "waiting for adoption";
    case Quarantined = "quarantined";
    case TemporaryHome = "in temporary home";

    public static function values(): array
    {
        return array_map(fn(PetAdoptionStatus $element): string => $element->value, self::cases());
    }
}
