<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;

class PetPolicy
{
    public function __construct() {}

    public function store(User $user): bool
    {
        return $user->hasShelterRole() || $user->hasAdminRole();
    }

    public function update(User $user): bool
    {
        return $user->hasShelterRole() || $user->hasAdminRole();
    }

    public function delete(User $user): bool
    {
        return $user->hasShelterRole() || $user->hasAdminRole();
    }

    public function accept(User $user, Pet $pet): bool
    {
        return $user->hasAdminRole() && $pet->is_accepted === false;
    }

    public function reject(User $user, Pet $pet): bool
    {
        return $user->hasAdminRole() && $pet->is_accepted === false;
    }

    public function favouriteCreate(User $user, Pet $pet): bool
    {
        return !$user->favourites()->where("pet_id", $pet->id)->exists();
    }

    public function favouriteDelete(User $user, Pet $pet): bool
    {
        return $user->favourites()->where("pet_id", $pet->id)->exists();
    }
}
