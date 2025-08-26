<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Pet;
use App\Models\PetShelter;
use App\Models\Preference;
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
    public const NUMBER_OF_PREFERENCES_PER_USER_TO_CREATE = 20;
    public const NUMBER_OF_TAGS_PER_PREFERENCE = 3;

    public function run(): void
    {
        Tag::factory()
            ->count(self::NUMBER_OF_TAGS_TO_CREATE)
            ->make()
            ->each(fn($tag) => Tag::firstOrCreate(["name" => $tag->name]));

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

            if (!$userHasExistingShelter && !$user->hasAdminRole()) {
                $randomShelter = $petShelters->random();
                $randomShelter->users()->attach($user->id);

                if (random_int(1, 100) <= 30) {
                    $user->update(["role" => "shelter"]);
                }
            }
        }

        $pets->each(fn(Pet $pet) => $pet->shelter()->associate($petShelters->random())->save());

        $tags = Tag::all();

        foreach ($users as $user) {
            Preference::factory()
                ->count(self::NUMBER_OF_PREFERENCES_PER_USER_TO_CREATE)
                ->for($user)
                ->create()
                ->each(function (Preference $preference) use ($tags): void {
                    $preferenceData = $preference->preferences;
                    $preferenceData["tags"] = $tags
                        ->random(DemoSeeder::NUMBER_OF_TAGS_PER_PREFERENCE)
                        ->pluck("name")
                        ->toArray();
                    $preference->update(["preferences" => $preferenceData]);
                });
        }
    }
}
