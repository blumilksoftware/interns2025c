<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Pet;
use App\Models\PetShelter;
use App\Utils\PetEnumMapper;
use App\Utils\UrlFormatHelper;
use Illuminate\Support\Facades\Log;

class PetService
{
    public function savePetToDB(array $petData, string $shelterUrl, string $adoptionUrl): void
    {
        $shelterBaseUrl = UrlFormatHelper::getBaseUrl($shelterUrl);
        $domain = parse_url($shelterBaseUrl, PHP_URL_HOST);

        $shelter = PetShelter::where("url", "like", "%" . $domain . "%")->first();

        if (!$shelter) {
            Log::warning("No shelter found for $shelterBaseUrl");

            return;
        }

        foreach ($petData["animals"] ?? [] as $animal) {
            Pet::query()->create([
                "name" => $animal["name"],
                "species" => PetEnumMapper::mapSpecies($animal["species"]),
                "sex" => PetEnumMapper::mapSex($animal["sex"]),
                "breed" => $animal["breed"] ?? null,
                "age" => $animal["age"] ?? null,
                "size" => $animal["size"] ?? null,
                "weight" => $animal["weight"] ?? null,
                "color" => $animal["color"] ?? null,
                "sterilized" => $animal["sterilized"] ?? null,
                "description" => $animal["description"],
                "health_status" => PetEnumMapper::mapHealthStatus($animal["health_status"] ?? null),
                "current_treatment" => $animal["current_treatment"] ?? null,
                "vaccinated" => $animal["vaccinated"] ?? null,
                "has_chip" => $animal["has_chip"] ?? null,
                "chip_number" => $animal["chip_number"] ?? null,
                "dewormed" => $animal["dewormed"] ?? null,
                "deflea_treated" => $animal["deflea_treated"] ?? null,
                "medical_tests" => $animal["medical_tests"] ?? null,
                "food_type" => $animal["food_type"] ?? null,
                "attitude_to_people" => $animal["attitude_to_people"] ?? null,
                "attitude_to_dogs" => $animal["attitude_to_dogs"] ?? null,
                "attitude_to_cats" => $animal["attitude_to_cats"] ?? null,
                "attitude_to_children" => $animal["attitude_to_children"] ?? null,
                "activity_level" => $animal["activity_level"] ?? null,
                "behavioral_notes" => $animal["behavioral_notes"] ?? null,
                "admission_date" => $animal["admission_date"] ?? null,
                "found_location" => $animal["found_location"] ?? null,
                "adoption_status" => $animal["adoption_status"] ?? null,
                "shelter_id" => $shelter->id,
                "adoption_url" => $adoptionUrl,
            ]);
        }
    }
}
