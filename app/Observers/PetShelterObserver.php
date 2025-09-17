<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\PetShelter;

class PetShelterObserver
{
    public function deleting(PetShelter $shelter): void
    {
        if ($shelter->isForceDeleting()) {
            $shelter->pets()->withTrashed()->forceDelete();
        } else {
            $shelter->pets()->delete();
        }
    }
}
