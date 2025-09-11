<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumValues;

enum PetActivityLevel: string
{
    use EnumValues;

    case VeryLow = "very low";
    case Low = "low";
    case Medium = "medium";
    case High = "high";
    case VeryHigh = "very high";
    case Unknown = "unknown";
}
