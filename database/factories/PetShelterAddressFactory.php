<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PetShelterAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetShelterAddressFactory extends Factory
{
    protected $model = PetShelterAddress::class;

    public function definition(): array
    {
        return [
            "address" => $this->faker->streetAddress(),
            "city" => $this->faker->city(),
            "postal_code" => $this->faker->postcode(),
            "latitude" => $this->faker->latitude(-90, 90),
            "longitude" => $this->faker->longitude(-180, 180),
        ];
    }
}
