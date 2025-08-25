<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class PetShelterAddressPolicy
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
}
