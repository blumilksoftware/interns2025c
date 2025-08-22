<?php

declare(strict_types=1);

namespace App\Enums;

enum PetAttitude: string
{
    case VeryLow = "very low";
    case Low = "low";
    case Medium = "medium";
    case High = "high";
    case VeryHigh = "very High";
    case Unkown = "unknown";
}
