<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Pet;
use App\Models\PetShelter;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public const NUMBER_OF_PET_SHELTERS_TO_CREATE = 50;
    public const NUMBER_OF_USERS_TO_CREATE = 100;
    public const NUMBER_OF_TAGS_TO_CREATE = 20;

    public function run(): void
    {
        Tag::factory()
            ->count(self::NUMBER_OF_TAGS_TO_CREATE)
            ->make()
            ->each(function ($tag): void {
                Tag::firstOrCreate(
                    ["name" => $tag->name],
                );
            });

        User::factory()->create([
            "email" => "user@example.com",
            "password" => Hash::make("password"),
            "role" => Role::User,
        ]);

        User::factory()->create([
            "email" => "shelter@example.com",
            "password" => Hash::make("password"),
            "role" => Role::ShelterEmployee,
        ]);

        User::factory()->create([
            "email" => "admin@example.com",
            "password" => Hash::make("password"),
            "role" => Role::Admin,
        ]);

        User::factory()->count(self::NUMBER_OF_USERS_TO_CREATE)->create();
        $users = User::all();

        $pets = Pet::factory()->count(10)->create();
        $petShelters = PetShelter::factory()->count(self::NUMBER_OF_PET_SHELTERS_TO_CREATE)->create();

        foreach ($users as $user) {
            $userHasExistingShelter = DB::table("pet_shelter_user")
                ->where("user_id", $user->id)
                ->exists();

            if (!$userHasExistingShelter) {
                $randomShelter = $petShelters->random();
                $randomShelter->users()->attach($user->id);

                if (random_int(1, 100) <= 30) {
                    $user->update(["role" => "shelter"]);
                }
            }
        }

        $pets->each(function (Pet $pet) use ($petShelters): void {
            $pet->shelter()->associate($petShelters->random());
            $pet->save();
        });
    }
}
