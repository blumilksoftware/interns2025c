<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumValues;

enum PetAdoptionStatus: string
{
    use EnumValues;

    case Adopted = "adopted";
    case WaitingForAdoption = "waiting for adoption";
    case Quarantined = "quarantined";
    case TemporaryHome = "in temporary home";
}
