<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PetShelter;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    public const NUMBER_OF_PET_SHELTERS_TO_CREATE = 50;
    public const NUMBER_OF_USERS_TO_CREATE = 100;

    public function run(): void
    {
        $users = User::factory()->count(self::NUMBER_OF_USERS_TO_CREATE)->create();
        $petShelters = PetShelter::factory()->count(self::NUMBER_OF_PET_SHELTERS_TO_CREATE)->create();

        foreach ($users as $user) {
            $userHasExistingShelter = DB::table("pet_shelter_user")
                ->where("user_id", $user->id)
                ->exists();

            if (!$userHasExistingShelter) {
                $randomShelter = $petShelters->random();
                $randomShelter->users()->attach($user->id);

                // Randomly assign 'shelter' role to only some users (e.g., 30% chance)
                if (rand(1, 100) <= 30) {
                    $user->update(["role" => "shelter"]);
                }
            }
        }
    }
}
