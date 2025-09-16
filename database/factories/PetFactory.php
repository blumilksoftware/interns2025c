<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PetAdoptionStatus;
use App\Enums\PetHealthStatus;
use App\Enums\PetSex;
use App\Enums\PetSpecies;
use App\Models\Pet;
use App\Models\PetShelter;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        $hasChip = $this->faker->optional()->boolean();
        $adoptionStatus = $this->faker->optional()->randomElement(PetAdoptionStatus::values());

        return [
            "name" => $this->faker->firstName(),
            "species" => $this->faker->randomElement(array_column(PetSpecies::cases(), "value")),
            "breed" => $this->faker->optional()->word(),
            "sex" => $this->faker->randomElement(array_column(PetSex::cases(), "value")),
            "age" => $this->faker->optional()->numberBetween(1, 20),
            "size" => $this->faker->optional()->randomElement(["small", "medium", "large"]),
            "weight" => $this->faker->optional()->randomFloat(2, 1, 80),
            "color" => $this->faker->optional()->safeColorName(),
            "sterilized" => $this->faker->optional()->boolean(),
            "description" => $this->faker->sentence(10),
            "health_status" => $this->faker->optional()->randomElement(array_column(PetHealthStatus::cases(), "value")),
            "current_treatment" => $this->faker->optional()->sentence(3),
            "vaccinated" => $this->faker->optional()->boolean(),
            "has_chip" => $hasChip,
            "chip_number" => $hasChip ? $this->faker->uuid() : null,
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
            "found_location" => $this->faker->optional()->city(),
            "adoption_status" => $adoptionStatus,
            "adoption_url" => $adoptionStatus === "adopted" ? null : $this->faker->optional()->url(),
            "shelter_id" => PetShelter::factory(),
            "is_accepted" => $this->faker->boolean(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Pet $pet): void {
            $tags = Tag::inRandomOrder()->take(rand(1, 3))->pluck("id");

            if ($tags->isNotEmpty()) {
                $pet->tags()->attach($tags);
            }
        });
    }
}
