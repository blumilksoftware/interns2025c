<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PetShelter;
use App\Models\PetShelterAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetShelterFactory extends Factory
{
    protected $model = PetShelter::class;

    public function definition(): array
    {
        return [
            "name" => $this->faker->company(),
            "phone" => $this->faker->phoneNumber(),
            "email" => $this->faker->email(),
            "description" => $this->faker->paragraph(),
            "url" => $this->faker->url(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (PetShelter $shelter): void {
            PetShelterAddress::factory()->for($shelter)->create();
        });
    }
}
