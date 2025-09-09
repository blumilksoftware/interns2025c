<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumValues;

enum PetAge: string
{
    use EnumValues;

    case Juvenile = "juvenile";
    case Adult = "adult";
    case Senior = "senior";
    case Unknown = "unknown";
}
