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
        $city = $this->faker->city;
        $address = $this->faker->streetAddress;
        $postalCode = $this->faker->postcode;

        $coordinates = $this->generateCoordinates();

        return [
            'pet_shelter_id' => PetShelter::factory(),
            'address' => $address,
            'city' => $city,
            'postal_code' => $postalCode,
            'latitude' => $coordinates['latitude'] ?? null,
            'longitude' => $coordinates['longitude'] ?? null,
        ];
    }

    protected function generateCoordinates(): array
    {
        return [
            'latitude' => $this->faker->latitude(-90, 90),
            'longitude' => $this->faker->longitude(-180, 180),
        ];
    }
}
