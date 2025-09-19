<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Favourite;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavouriteFactory extends Factory
{
    protected $model = Favourite::class;

    public function definition(): array
    {
        return [
            "user_id" => User::inRandomOrder()->first()?->id ?? User::factory(),
            "pet_id" => Pet::inRandomOrder()->first()?->id ?? Pet::factory(),
        ];
    }
}
