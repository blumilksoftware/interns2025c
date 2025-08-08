<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function __construct(...$args)
    {
        parent::__construct(...$args);
        $this->faker = \Faker\Factory::create("pl_PL");
    }

    public function definition(): array
    {
        return [
            "name" => $this->faker->name(),
            "email" => $this->faker->unique()->safeEmail(),
            "email_verified_at" => now(),
            "password" => static::$password ??= Hash::make("password"),
            "two_factor_secret" => null,
            "two_factor_recovery_codes" => null,
            "remember_token" => Str::random(10),
            "role" => $this->chooseRandomRole(),
        ];
    }

    public function chooseRandomRole(): string
    {
        return $this->faker->randomElement([Role::ADMIN, Role::USER])->value;
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            "email_verified_at" => null,
        ]);
    }
}
