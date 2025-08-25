<?php

declare(strict_types=1);

namespace App\Actions\Jetstream;

use App\Models\User;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    public function delete(User $user): void
    {
        $user->petShelters()->detach();
        $user->tokens->each->delete();
        $user->delete();
    }
}
