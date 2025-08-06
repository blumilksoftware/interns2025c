<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PetShelterAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\PetShelter>
 */
class PetShelterFactory extends Factory
{
    public function definition(): array
    {
        return [
            "name" => $this->faker->company(),
            "phone" => $this->faker->phoneNumber(),
            "email" => $this->faker->email(),
            "description" => $this->faker->paragraph(),
            "address_id" => PetShelterAddress::factory(),
        ];
    }
}
