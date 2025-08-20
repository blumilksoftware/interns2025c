<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Preference;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreferenceFactory extends Factory
{
    protected $model = Preference::class;

    public function definition(): array
    {
        return [
            "user_id" => User::factory(),
            "name" => $this->faker->words(2, true),
        ];
    }
}
