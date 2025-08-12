<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PetShelterAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PetShelterAddress>
 */
class PetShelterAddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            "address" => $this->faker->streetAddress(),
            "city" => $this->faker->city(),
            "postal_code" => $this->faker->postcode(),
        ];
    }
}
