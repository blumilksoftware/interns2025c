<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\PetShelter;
use App\Policies\PetShelterPolicy;
use App\Models\PetShelterAddress;
use App\Policies\PetShelterAddressPolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Pet;
use App\Policies\PetPolicy;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected array $policies = [
        Pet::class => PetPolicy::class,
        PetShelter::class => PetShelterPolicy::class,
        PetShelterAddress::class => PetShelterAddressPolicy::class,
        User::class => UserPolicy::class,
    ];
}
