<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    public function definition(): array
    {
        $petBehavioralTraits = [
            "happy", "playful", "curious", "affectionate", "lazy", "energetic",
            "loyal", "protective", "gentle", "mischievous", "vocal", "independent",
            "clingy", "timid", "friendly", "alert", "food-motivated", "sleepy",
            "adventurous", "shy",
        ];

        return [
            "name" => $this->faker->randomElement($petBehavioralTraits),
        ];
    }
}
