<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumValues;

enum PetHealthStatus: string
{
    use EnumValues;

    case Healthy = "healthy";
    case Sick = "sick";
    case Recovering = "recovering";
    case Critical = "critical";
    case Unknown = "unknown";
}
