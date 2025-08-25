<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\UrlFormatHelper;
use App\Models\Pet;
use App\Models\PetShelter;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PetService
{
    public function store(array $petData, string $shelterUrl, string $extractedPetAdoptionUrl): void
    {
        if (empty($petData)) {
            Log::warning("Pet data is empty or invalid for pet at URL: $extractedPetAdoptionUrl");

            return;
        }

        if (empty($shelterUrl) && empty($extractedPetAdoptionUrl)) {
            Log::error("Both shelter URL and extracted pet adoption URL are empty for pet at URL $extractedPetAdoptionUrl");

            return;
        }

        $shelterId = $this->findShelterByItsUrlHost($shelterUrl)?->id;

        foreach ($petData["animals"] ?? [] as $animal) {
            $identifyingAttributes = [
                "name" => $animal["name"],
                "species" => $animal["species"],
                "behavioral_notes" => $animal["behavioral_notes"] ?? null,
                "shelter_id" => $shelterId,
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
                "adoption_url" => $extractedPetAdoptionUrl,
            ];

            Pet::query()->updateOrCreate($identifyingAttributes, $attributes);
        }

        $tags = $this->extractTagsFromText(
            $petData["tags"] ?? "",
        );

        foreach ($tags as $tag) {
            Tag::query()->firstOrCreate(["name" => $tag]);
        }
    }

    private function findShelterByItsUrlHost(string $shelterUrl): PetShelter|null
    {
        if (!$shelterUrl) {
            Log::error("Shelter URL is empty or invalid.");

            return null;
        }

        $host = UrlFormatHelper::getUrlHost($shelterUrl);

        $shelter = PetShelter::where("url", "like", "%" . $host . "%")->first();

        if ($shelter) {
            return $shelter;
        }
        Log::warning("No shelter found for URL: $shelterUrl");

        return null;
    }

    private function extractTagsFromText(string $text, string $delimiter = " "): array
    {
        if (empty($text)) {
            return [];
        }

        return Str::of($text)
            ->explode($delimiter)
            ->map(fn(string $tag): string => trim($tag))
            ->filter()
            ->unique()
            ->toArray();
    }
}
