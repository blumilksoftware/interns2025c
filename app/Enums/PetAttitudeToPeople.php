<?php

declare(strict_types=1);

namespace App\Enums;

enum PetAttitudeToPeople: string
{
    case Friendly = "friendly";
    case Aggressive = "aggressive";
    case Calm = "calm";
    case Shy = "shy";
    case Fearful = "fearful";
    case Unknown = "unknown";

    public static function values(): array
    {
        return array_map(fn($e) => $e->value, self::cases());
    }
}
