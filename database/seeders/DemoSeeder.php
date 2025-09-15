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
    public const NUMBER_OF_USERS_TO_CREATE = 100;
    public const NUMBER_OF_PETS_TO_CREATE = 100;
    public const NUMBER_OF_TAGS_TO_CREATE = 20; 
    public const NUMBER_OF_PREFERENCES_PER_USER_TO_CREATE = 20;
    public const NUMBER_OF_TAGS_PER_PREFERENCE = 3;
    public const NUMBER_OF_TAGS_PER_PET = 3;

    public function run(): void
    {
        Tag::factory()
            ->count(self::NUMBER_OF_TAGS_TO_CREATE)
            ->make()
            ->each(fn(Tag $tag): Tag => Tag::firstOrCreate(["name" => $tag->name]));

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

        $pets = Pet::factory()->count(self::NUMBER_OF_PETS_TO_CREATE)->create();

        $pets->each(function (Pet $pet): void {
            $pet->shelter->address()->update([
                "address" => fake()->address(),
                "city" => fake()->city(),
                "postal_code" => fake()->postcode(),
            ]);
        });

        $petShelters = PetShelter::factory()->count(self::NUMBER_OF_PET_SHELTERS_TO_CREATE)->create();

        $tags = Tag::all();

        foreach ($users as $user) {
            $userHasExistingShelter = DB::table("pet_shelter_user")
                ->where("user_id", $user->id)
                ->exists();

            if (!$userHasExistingShelter && !$user->hasAdminRole()) {
                $randomShelter = $shelters->random();
                $randomShelter->users()->attach($user->id);

                if (random_int(1, 100) <= 30) {
                    $user->update(["role" => Role::ShelterEmployee]);
                }
            }
        }

        foreach ($users as $user) {
            Preference::factory()
                ->count(self::NUMBER_OF_PREFERENCES_PER_USER_TO_CREATE)
                ->for($user)
                ->create()
                ->each(function (Preference $preference) use ($tags): void {
                    $preferenceData = $preference->preferences;
                    $preferenceData["tags"] = $tags
                        ->random(self::NUMBER_OF_TAGS_PER_PREFERENCE)
                        ->pluck("name")
                        ->toArray();
                    $preference->update(["preferences" => $preferenceData]);
                });
        }

        $pets->each(function (Pet $pet) use ($tags): void {
            $pet->tags()->sync(
                $tags->random(self::NUMBER_OF_TAGS_PER_PET)->pluck("id")->unique()->toArray()
            );
        });
    }
}
