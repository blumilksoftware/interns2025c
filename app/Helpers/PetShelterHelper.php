<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\PetShelter;

class PetShelterHelper
{
    public static function getAllPetShelterUrls(): array
    {
        return PetShelter::query()
            ->whereNotNull("url")
            ->pluck("url")
            ->filter()
            ->unique()
            ->toArray();
    }
}
