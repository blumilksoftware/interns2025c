<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Pet;
use App\Models\PetShelter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pet>
 */
class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        return [
            "name" => $this->faker->firstName(),
            "species" => $this->faker->randomElement(["dog", "cat", "rabbit", "bird"]),
            "breed" => $this->faker->optional()->word(),
            "gender" => $this->faker->randomElement(["male", "female"]),
            "age" => $this->faker->optional()->numberBetween(1, 20),
            "size" => $this->faker->optional()->randomElement(["small", "medium", "large"]),
            "weight" => $this->faker->optional()->randomFloat(2, 1, 80),
            "color" => $this->faker->optional()->safeColorName(),
            "sterilized" => $this->faker->optional()->boolean(),
            "description" => $this->faker->sentence(10),
            "health_status" => $this->faker->optional()->word(),
            "current_treatment" => $this->faker->optional()->sentence(3),
            "vaccinated" => $this->faker->optional()->boolean(),
            "has_chip" => $this->faker->optional()->boolean(),
            "chip_number" => $this->faker->optional()->uuid(),
            "dewormed" => $this->faker->optional()->boolean(),
            "deflea_treated" => $this->faker->optional()->boolean(),
            "medical_tests" => $this->faker->optional()->sentence(3),
            "food_type" => $this->faker->optional()->word(),
            "attitude_to_people" => $this->faker->optional()->randomElement(["friendly", "shy", "aggressive", "neutral"]),
            "attitude_to_dogs" => $this->faker->optional()->randomElement(["friendly", "shy", "aggressive", "neutral"]),
            "attitude_to_cats" => $this->faker->optional()->randomElement(["friendly", "shy", "aggressive", "neutral"]),
            "attitude_to_children" => $this->faker->optional()->randomElement(["friendly", "shy", "aggressive", "neutral"]),
            "activity_level" => $this->faker->optional()->randomElement(["low", "medium", "high"]),
            "behavioral_notes" => $this->faker->optional()->sentence(5),
            "admission_date" => $this->faker->optional()->date(),
            "quarantine_end_date" => $this->faker->optional()->date(),
            "found_location" => $this->faker->optional()->city(),
            "adoption_status" => $this->faker->optional()->randomElement(["available", "adopted", "pending", "fostered"]),
            // "shelter_id" => PetShelter::factory(),
        ];
    }
}
