<?php

declare(strict_types=1);

namespace App\Enums;

enum PetActivityLevel: String
{
    case VERY_LOW = "very low";
    case LOW = "low";
    case MEDIUM = "medium";
    case HIGH = "high";
    case VERY_HIGH = "very High";
    case UNKNOWN = "unknown";
}
