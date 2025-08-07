<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Pet;
use App\Models\PetShelter;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $pets = Pet::factory()->count(10)->create();
        $shelters = PetShelter::factory()->count(10)->create();

        $pets->each(function (Pet $pet) use ($shelters): void {
            $pet->shelter()->associate($shelters->random());
            $pet->save();
        });
    }
}
