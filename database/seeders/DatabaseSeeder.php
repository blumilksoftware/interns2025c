<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Call seeders in dependency order
        $this->call([
            UserSeeder::class,
            PetShelterSeeder::class,
        ]);
    }
}
