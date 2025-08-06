<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    const NUMBER_OF_USERS_TO_CREATE = 100;
    public function run(): void
    {
        User::factory()->count(self::NUMBER_OF_USERS_TO_CREATE)->create();
    }
}
