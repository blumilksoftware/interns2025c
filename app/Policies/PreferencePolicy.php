<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Preference;
use App\Models\User;

class PreferencePolicy
{
    public function update(User $user, Preference $preference): bool
    {
        return $user->id === $preference->user_id;
    }

    public function delete(User $user, Preference $preference): bool
    {
        return $user->id === $preference->user_id;
    }
}
