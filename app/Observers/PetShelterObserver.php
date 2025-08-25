<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\PetShelter;

class PetShelterObserver
{
    public function created(PetShelter $shelter): void
    {
        if ($shelter->address()->doesntExist()) {
            $shelter->address()->create();
        }
    }
}
