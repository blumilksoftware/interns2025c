<?php

namespace Database\Seeders;

use App\Models\PetShelter;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PetShelterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $petShelters = PetShelter::factory()->count(10)->create();
        $users = User::all();
        
        if ($users->count() === 0 || $petShelters->count() === 0) return;
        
        foreach ($users as $user) {
            $hasExistingShelter = DB::table('pet_shelter_user')
                ->where('user_id', $user->id)
                ->exists();
            
            if (!$hasExistingShelter) {
                $randomShelter = $petShelters->random();
                $randomShelter->users()->attach($user->id);
            }
        }
        
    }
}