<?php

declare(strict_types=1);

namespace App\Enums;

enum PetSize: string
{
    case Small = "small";
    case Medium = "medium";
    case Large = "large";
    case Giant = "giant";
}
