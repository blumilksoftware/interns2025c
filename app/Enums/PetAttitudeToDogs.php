<?php

declare(strict_types=1);

namespace App\Enums;

enum PetAttitudeToDogs: string
{
    case Friendly = "friendly";
    case Aggressive = "aggressive";
    case Neutral = "neutral";
    case Playful = "playful";
    case Unknown = "unknown";

    public static function values(): array
    {
        return array_map(fn($e) => $e->value, self::cases());
    }
}
