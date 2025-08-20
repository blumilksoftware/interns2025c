<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;

class UserPolicy
{
    public function delete(User $authenticatedUser, User $targetUser): bool
    {
        return $authenticatedUser->haveAdminRole() && !$targetUser->haveAdminRole();
    }
}
