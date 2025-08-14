<?php

declare(strict_types=1);

namespace App\Enums;

enum PetSpecies: string
{
    case Dog = "dog";
    case Cat = "cat";
    case Bird = "bird";
    case Rabbit = "rabbit";
    case Reptile = "reptile";
    case Other = "other";
}
