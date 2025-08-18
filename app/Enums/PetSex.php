<?php

declare(strict_types=1);

namespace App\Enums;

enum PetSex: string
{
    case Male = "male";
    case Female = "female";
    case Unknown = "unknown";
}
