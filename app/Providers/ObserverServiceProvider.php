<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Pet;
use App\Models\PetShelter;
use App\Observers\PetObserver;
use App\Observers\PetShelterObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        PetShelter::observe(PetShelterObserver::class);
        Pet::observe(PetObserver::class);
    }
}
