<?php

declare(strict_types=1);

namespace App\Enums;

enum PetAdoptionStatus: String
{
    case ADOPTED = "adopted";
    case WAITING_FOR_ADOPTION = "waiting for adoption";
    case QUARANTINED = "quarantined";
}
