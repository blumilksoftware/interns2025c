<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumValues;

enum PetSpecies: string
{
    use EnumValues;

    case Dog = "dog";
    case Cat = "cat";
    case Other = "other";
}
