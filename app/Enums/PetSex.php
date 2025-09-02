<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumValues;

enum PetSex: string
{
    use EnumValues;

    case Male = "male";
    case Female = "female";
    case Unknown = "unknown";
}
