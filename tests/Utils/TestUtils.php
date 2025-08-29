<?php

declare(strict_types=1);

namespace Tests\Utils;

use App\Models\Preference;
use App\Models\User;

trait TestUtils
{
    protected function createUser(): User
    {
        return User::factory()->create();
    }

    protected function createUserWithPreference(array $override = []): array
    {
        $user = User::factory()->create();
        $preference = Preference::factory()->for($user)->create($override);

        return [$user, $preference];
    }

    protected function preferenceData(array $override = []): array
    {
        return array_merge(
            Preference::factory()->make()->toArray(),
            $override,
        );
    }
}
