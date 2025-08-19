<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PetShelter;
use App\Models\PetShelterAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetShelterAddressFactory extends Factory
{
    protected $model = PetShelterAddress::class;

    public function definition(): array
    {
        return [
            "pet_shelter_id" => PetShelter::factory(),
            "address" => $this->faker->streetAddress,
            "city" => $this->faker->city,
            "postal_code" => $this->faker->postcode,
        ];
    }
}
