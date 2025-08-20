<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Pet;
use App\Models\PetShelter;
use App\Models\Tag;
use App\Utils\UrlFormatHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

        $tags = Str::of($petData["animals"][0]["behavioral_notes"] ?? "")
            ->explode(" ")
            ->map(fn($tag) => trim($tag))
            ->filter()
            ->unique()
            ->toArray();

        foreach ($tags as $tag) {
            Tag::query()->firstOrCreate(["name" => $tag]);
            Log::info("Tag created: $tag");
        }

        foreach ($petData["animals"] ?? [] as $animal) {
            $identifyingAttributes = [
                "name" => $animal["name"],
                "species" => $animal["species"],
                "behavioral_notes" => $animal["behavioral_notes"] ?? null,
                "shelter_id" => $shelter->id,
                "sex" => $animal["sex"],
                "breed" => $animal["breed"] ?? null,
                "weight" => $animal["weight"] ?? null,
                "chip_number" => $animal["chip_number"] ?? null,
                "found_location" => $animal["found_location"] ?? null,
            ];

            $attributes = [
                "age" => $animal["age"] ?? null,
                "size" => $animal["size"] ?? null,
                "color" => $animal["color"] ?? null,
                "sterilized" => $animal["sterilized"] ?? null,
                "description" => $animal["description"],
                "health_status" => $animal["health_status"] ?? null,
                "current_treatment" => $animal["current_treatment"] ?? null,
                "vaccinated" => $animal["vaccinated"] ?? null,
                "dewormed" => $animal["dewormed"] ?? null,
                "deflea_treated" => $animal["deflea_treated"] ?? null,
                "medical_tests" => $animal["medical_tests"] ?? null,
                "food_type" => $animal["food_type"] ?? null,
                "attitude_to_people" => $animal["attitude_to_people"] ?? null,
                "attitude_to_dogs" => $animal["attitude_to_dogs"] ?? null,
                "attitude_to_cats" => $animal["attitude_to_cats"] ?? null,
                "attitude_to_children" => $animal["attitude_to_children"] ?? null,
                "activity_level" => $animal["activity_level"] ?? null,
                "adoption_status" => $animal["adoption_status"] ?? null,
                "admission_date" => isset($animal["admission_date"]) ? Carbon::parse($animal["admission_date"]) : null,
                "has_chip" => $animal["has_chip"] ?? null,
                "adoption_url" => $adoptionUrl,
            ];

            Pet::query()->updateOrCreate($identifyingAttributes, $attributes);
        }
    }
}
