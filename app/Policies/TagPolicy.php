<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class TagPolicy
{
    public function __construct() {}

    public function store(User $user): bool
    {
        return $user->haveAdminRole();
    }

    public function update(User $user): bool
    {
        return $user->haveAdminRole();
    }

    public function delete(User $user): bool
    {
        return $user->haveAdminRole();
    }
}
