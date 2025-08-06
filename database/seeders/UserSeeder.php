<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public const NUMBER_OF_USERS_TO_CREATE = 100;

    public function run(): void
    {
        User::factory()->count(self::NUMBER_OF_USERS_TO_CREATE)->create();
    }
}
