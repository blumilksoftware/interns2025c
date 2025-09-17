<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PetActivityLevel;
use App\Enums\PetAdoptionStatus;
use App\Enums\PetAge;
use App\Enums\PetAttitude;
use App\Enums\PetHealthStatus;
use App\Enums\PetSex;
use App\Enums\PetSize;
use App\Enums\PetSpecies;
use App\Models\Preference;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreferenceFactory extends Factory
{
    protected $model = Preference::class;

    public function definition(): array
    {
        $address = $this->faker->streetAddress;
        $city = $this->faker->city;
        $postalCode = $this->faker->postcode;
        $latitude = $this->faker->latitude(-90, 90);
        $longitude = $this->faker->longitude(-180, 180);

        return [
            "user_id" => User::factory(),
            "preferences" => [
                "species" => $this->generateWeightedEnumArray(PetSpecies::values()),
                "breed" => $this->generateWeightedArray(["labrador", "siamese", "bulldog"]),
                "sex" => $this->generateWeightedEnumArray(PetSex::values()),
                "size" => $this->generateWeightedEnumArray(PetSize::values()),
                "age" => $this->generateWeightedEnumArray(PetAge::values()),
                "health_status" => $this->generateWeightedEnumArray(PetHealthStatus::values()),
                "vaccinated" => $this->generateWeightedBoolean(),
                "sterilized" => $this->generateWeightedBoolean(),
                "has_chip" => $this->generateWeightedBoolean(),
                "dewormed" => $this->generateWeightedBoolean(),
                "deflea_treated" => $this->generateWeightedBoolean(),
                "adoption_status" => $this->generateWeightedEnumArray(PetAdoptionStatus::values()),
                "attitude" => $this->generateWeightedEnumArray(PetAttitude::values()),
                "activity_level" => $this->generateWeightedEnumArray(PetActivityLevel::values()),
                "tags" => $this->generateWeightedArray(["playful", "calm", "friendly"]),
            ],
            "address" => $address,
            "city" => $city,
            "postal_code" => $postalCode,
            "latitude" => $latitude,
            "longitude" => $longitude,
            "radius_in_km" => $this->faker->numberBetween(5, 50),
        ];
    }

    protected function generateWeightedEnumArray(array $values): array
    {
        $count = $this->faker->numberBetween(1, count($values));
        $chosen = $this->faker->randomElements($values, $count);

        return array_map(fn($v): array => ["value" => $v, "weight" => 1], $chosen);
    }

    protected function generateWeightedArray(array $values): array
    {
        $count = $this->faker->numberBetween(1, count($values));
        $chosen = $this->faker->randomElements($values, $count);

        return array_map(fn($v): array => ["value" => $v, "weight" => 1], $chosen);
    }

    protected function generateWeightedBoolean(): array
    {
        return [
            "value" => $this->faker->boolean(),
            "weight" => 1,
        ];
    }
}
