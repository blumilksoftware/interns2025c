<?php

namespace Database\Seeders;

use App\Models\PetShelter;
use App\Models\User;
use App\Enums\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PetShelterSeeder extends Seeder
{
    const NUMBER_OF_PET_SHELTERS_TO_CREATE = 50;
    public function run(): void
    {
        $petShelters = PetShelter::factory()->count(self::NUMBER_OF_PET_SHELTERS_TO_CREATE)->create();
        $users = User::all();
        
        foreach ($users as $user) {

            $userHasExistingShelter = DB::table('pet_shelter_user')
                ->where('user_id', $user->id)
                ->exists();
            
            if (!$userHasExistingShelter) {
                $randomShelter = $petShelters->random();
                $randomShelter->users()->attach($user->id);

                // Randomly assign 'shelter' role to only some users (e.g., 30% chance)
                if (rand(1, 100) <= 30) {
                    $user->update(['role' => Role::SHELTER->value]);
                }
            }
        }
        
    }
}