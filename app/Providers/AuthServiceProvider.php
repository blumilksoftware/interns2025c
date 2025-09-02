<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Pet;
use App\Models\PetShelter;
use App\Models\PetShelterAddress;
use App\Models\Preference;
use App\Models\User;
use App\Policies\PetPolicy;
use App\Policies\PetShelterAddressPolicy;
use App\Policies\PetShelterPolicy;
use App\Policies\PreferencePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected array $policies = [
        Pet::class => PetPolicy::class,
        PetShelter::class => PetShelterPolicy::class,
        PetShelterAddress::class => PetShelterAddressPolicy::class,
        User::class => UserPolicy::class,
        Preference::class => PreferencePolicy::class,
    ];
}
