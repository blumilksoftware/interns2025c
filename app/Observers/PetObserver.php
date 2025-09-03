<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Pet;

class PetObserver
{
    public function deleting(Pet $pet): void
    {
        $pet->tags()->detach();
    }
}
