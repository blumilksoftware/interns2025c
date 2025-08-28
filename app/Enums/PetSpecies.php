<?php

declare(strict_types=1);

namespace App\Enums;

enum PetSpecies: string
{
    case Dog = "dog";
    case Cat = "cat";
    case Other = "other";
}
