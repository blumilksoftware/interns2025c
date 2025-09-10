<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Pet;
use App\Models\PetShelter;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PetsOnlySeeder extends Seeder
{
    public function run(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === "pgsql") {
            DB::statement("TRUNCATE TABLE pet_tag RESTART IDENTITY CASCADE");
            DB::statement("TRUNCATE TABLE pets RESTART IDENTITY CASCADE");
        } elseif ($driver === "mysql") {
            DB::statement("SET FOREIGN_KEY_CHECKS=0;");
            DB::table("pet_tag")->truncate();
            DB::table("pets")->truncate();
            DB::statement("SET FOREIGN_KEY_CHECKS=1;");
        } else {
            DB::table("pet_tag")->truncate();
            DB::table("pets")->truncate();
        }

        if (PetShelter::count() < 5) {
            PetShelter::factory()->count(5 - PetShelter::count())->create();
        }

        $requiredTagNames = [
            "friendly", "playful", "calm", "active", "gentle", "smart", "independent", "curious",
            "good with kids", "good with dogs", "good with cats", "house trained", "leash trained", "low shedding",
        ];

        foreach ($requiredTagNames as $name) {
            Tag::firstOrCreate(["name" => $name]);
        }

        $tags = Tag::whereIn("name", $requiredTagNames)->get();
        $shelters = PetShelter::all();

        $curatedPets = [
            ["name" => "Buddy",   "species" => "dog", "breed" => "Labrador Retriever", "sex" => "male",   "age" => 24, "size" => "large",  "weight" => 28.5, "color" => "yellow", "adoption_status" => "available", "tags" => ["friendly", "playful", "good with kids"]],
            ["name" => "Luna",    "species" => "cat", "breed" => "Siamese",            "sex" => "female", "age" => 12, "size" => "small",  "weight" => 3.8,  "color" => "seal point", "adoption_status" => "available", "tags" => ["curious", "smart", "independent"]],
            ["name" => "Max",     "species" => "dog", "breed" => "German Shepherd",    "sex" => "male",   "age" => 36, "size" => "large",  "weight" => 32.0, "color" => "black and tan", "adoption_status" => "available", "tags" => ["smart", "active", "leash trained"]],
            ["name" => "Bella",   "species" => "dog", "breed" => "Golden Retriever",   "sex" => "female", "age" => 48, "size" => "large",  "weight" => 29.0, "color" => "golden", "adoption_status" => "available", "tags" => ["gentle", "friendly", "good with kids"]],
            ["name" => "Oliver",  "species" => "cat", "breed" => "British Shorthair",  "sex" => "male",   "age" => 24, "size" => "medium", "weight" => 5.5,  "color" => "blue", "adoption_status" => "available", "tags" => ["calm", "low shedding"]],
            ["name" => "Milo",    "species" => "cat", "breed" => "Maine Coon",         "sex" => "male",   "age" => 36, "size" => "large",  "weight" => 7.2,  "color" => "brown tabby", "adoption_status" => "available", "tags" => ["friendly", "good with kids"]],
            ["name" => "Coco",    "species" => "dog", "breed" => "Poodle",             "sex" => "female", "age" => 60, "size" => "medium", "weight" => 18.0, "color" => "white", "adoption_status" => "available", "tags" => ["smart", "low shedding", "house trained"]],
            ["name" => "Charlie", "species" => "dog", "breed" => "Beagle",             "sex" => "male",   "age" => 24, "size" => "medium", "weight" => 11.0, "color" => "tricolor", "adoption_status" => "available", "tags" => ["curious", "playful"]],
            ["name" => "Rocky",   "species" => "dog", "breed" => "Boxer",              "sex" => "male",   "age" => 48, "size" => "large",  "weight" => 30.5, "color" => "fawn", "adoption_status" => "available", "tags" => ["active", "friendly"]],
            ["name" => "Daisy",   "species" => "dog", "breed" => "Bulldog",            "sex" => "female", "age" => 36, "size" => "medium", "weight" => 22.0, "color" => "brindle", "adoption_status" => "available", "tags" => ["gentle", "calm"]],
            ["name" => "Simba",   "species" => "cat", "breed" => "Bengal",             "sex" => "male",   "age" => 24, "size" => "medium", "weight" => 4.5,  "color" => "brown spotted", "adoption_status" => "available", "tags" => ["active", "curious"]],
            ["name" => "Chloe",   "species" => "cat", "breed" => "Ragdoll",            "sex" => "female", "age" => 36, "size" => "large",  "weight" => 6.0,  "color" => "seal bicolor", "adoption_status" => "available", "tags" => ["gentle", "friendly"]],
            ["name" => "Nala",    "species" => "cat", "breed" => "Sphynx",             "sex" => "female", "age" => 24, "size" => "medium", "weight" => 3.2,  "color" => "pink", "adoption_status" => "available", "tags" => ["friendly", "curious"]],
            ["name" => "Sadie",   "species" => "dog", "breed" => "Dachshund",          "sex" => "female", "age" => 72, "size" => "small",  "weight" => 8.0,  "color" => "red", "adoption_status" => "available", "tags" => ["independent", "house trained"]],
            ["name" => "Zeus",    "species" => "dog", "breed" => "Rottweiler",         "sex" => "male",   "age" => 60, "size" => "large",  "weight" => 45.0, "color" => "black and tan", "adoption_status" => "available", "tags" => ["smart", "leash trained"]],
            ["name" => "Lily",    "species" => "dog", "breed" => "Yorkshire Terrier",  "sex" => "female", "age" => 48, "size" => "small",  "weight" => 3.2,  "color" => "blue and tan", "adoption_status" => "available", "tags" => ["playful", "low shedding"]],
            ["name" => "Leo",     "species" => "cat", "breed" => "Russian Blue",       "sex" => "male",   "age" => 36, "size" => "medium", "weight" => 4.2,  "color" => "blue", "adoption_status" => "available", "tags" => ["calm", "smart"]],
            ["name" => "Misty",   "species" => "cat", "breed" => "Persian",            "sex" => "female", "age" => 60, "size" => "medium", "weight" => 4.0,  "color" => "white", "adoption_status" => "available", "tags" => ["gentle", "low shedding"]],
            ["name" => "Finn",    "species" => "dog", "breed" => "Border Collie",      "sex" => "male",   "age" => 24, "size" => "medium", "weight" => 19.0, "color" => "black and white", "adoption_status" => "available", "tags" => ["smart", "active"]],
            ["name" => "Zoe",     "species" => "cat", "breed" => "Norwegian Forest",   "sex" => "female", "age" => 48, "size" => "large",  "weight" => 5.8,  "color" => "brown tabby", "adoption_status" => "available", "tags" => ["friendly", "curious"]],
        ];

        foreach ($curatedPets as $data) {
            $descBySpecies = [
                "dog" => "Lubi spacery i zabawę, łagodny w domu i towarzyski na zewnątrz.",
                "cat" => "Ciekawska i spokojna, lubi wylegiwać się na słońcu i bawić wędką.",
            ];
            $healthPool = ["healthy", "healthy", "healthy", "recovering"];
            $health = $healthPool[array_rand($healthPool)];
            $currentTreatment = $health === "recovering" ? "Krótka rekonwalescencja po szczepieniu, zalecany odpoczynek." : null;
            $sterilized = rand(0, 1) === 1;
            $vaccinated = rand(0, 1) === 1;
            $hasChip = rand(0, 1) === 1;
            $chip = $hasChip ? strtoupper(Str::random(12)) : null;
            $dewormed = rand(0, 1) === 1;
            $deflea = rand(0, 1) === 1;
            $medicalTests = $health === "healthy" ? "basic: negative" : "blood: normal; xray: clear";
            $food = collect(["dry", "wet", "mixed"])->random();
            $behavioral = $data["species"] === "dog" ? "Spokojny w domu, energiczny na zewnątrz." : "Lubi drapaki i ciche otoczenie.";
            $admission = now()->subDays(rand(7, 180));
            $city = collect(["Warsaw", "Krakow", "Gdansk", "Wroclaw", "Poznan"])->random();
            $adoptionUrl = "https://adopt.local/pets/" . Str::slug($data["name"] . " " . $data["breed"]);

            $pet = Pet::create([
                "name" => $data["name"],
                "adoption_url" => $adoptionUrl,
                "species" => $data["species"],
                "breed" => $data["breed"],
                "sex" => $data["sex"],
                "age" => $data["age"],
                "size" => $data["size"],
                "weight" => $data["weight"],
                "color" => $data["color"],
                "sterilized" => $sterilized,
                "description" => $descBySpecies[$data["species"]] ?? "Przyjazny i towarzyski.",
                "health_status" => $health,
                "current_treatment" => $currentTreatment,
                "vaccinated" => $vaccinated,
                "has_chip" => $hasChip,
                "chip_number" => $chip,
                "dewormed" => $dewormed,
                "deflea_treated" => $deflea,
                "medical_tests" => $medicalTests,
                "food_type" => $food,
                "attitude_to_people" => "friendly",
                "attitude_to_dogs" => "friendly",
                "attitude_to_cats" => "neutral",
                "attitude_to_children" => "gentle",
                "activity_level" => collect(["low", "medium", "high"])->random(),
                "behavioral_notes" => $behavioral,
                "admission_date" => $admission,
                "found_location" => $city,
                "adoption_status" => $data["adoption_status"],
                "shelter_id" => $shelters->random()->id,
                "is_accepted" => true,
            ]);

            $tagIds = $tags->whereIn("name", $data["tags"])->pluck("id")->toArray();

            if (!empty($tagIds)) {
                $pet->tags()->sync($tagIds);
            }
        }
    }
}
