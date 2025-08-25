<?php

declare(strict_types=1);

namespace App\Enums;

enum PetAdoptionStatus: string
{
    case Available = "available";
    case Pending = "pending";
    case Adopted = "adopted";
    case Quarantined = "quarantined";
    case Unknown = "unknown";

    public static function values(): array
    {
        return array_map(fn($e) => $e->value, self::cases());
    }
}
