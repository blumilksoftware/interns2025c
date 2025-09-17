<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Pet;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\JsonResource;

class PetShowResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Pet $pet */
        $pet = $this->resource;

        return [
            "id" => $pet->id,
            "name" => $pet->name,
            "adoption_url" => $pet->adoption_url,
            "species" => $pet->species,
            "breed" => $pet->breed,
            "sex" => $pet->sex,
            "age" => $pet->age,
            "size" => $pet->size,
            "weight" => $pet->weight,
            "color" => $pet->color,
            "sterilized" => $pet->sterilized,
            "description" => $pet->description,
            "health_status" => $pet->health_status,
            "current_treatment" => $pet->current_treatment,
            "vaccinated" => $pet->vaccinated,
            "has_chip" => $pet->has_chip,
            "chip_number" => $pet->chip_number,
            "dewormed" => $pet->dewormed,
            "deflea_treated" => $pet->deflea_treated,
            "medical_tests" => $pet->medical_tests,
            "food_type" => $pet->food_type,
            "attitude_to_people" => $pet->attitude_to_people,
            "attitude_to_dogs" => $pet->attitude_to_dogs,
            "attitude_to_cats" => $pet->attitude_to_cats,
            "attitude_to_children" => $pet->attitude_to_children,
            "activity_level" => $pet->activity_level,
            "behavioral_notes" => $pet->behavioral_notes,
            "admission_date" => $pet->admission_date ? $pet->admission_date->toDateString() : null,
            "quarantine_end_date" => $pet->quarantine_end_date ? $pet->quarantine_end_date->toDateString() : null,
            "found_location" => $pet->found_location,
            "adoption_status" => $pet->adoption_status,
            "shelter" => $pet->shelter ? [
                "id" => $pet->shelter->id,
                "name" => $pet->shelter->name,
                "phone" => $pet->shelter->phone,
                "email" => $pet->shelter->email,
                "address" => $pet->shelter->address ? [
                    "address" => $pet->shelter->address->address,
                    "city" => $pet->shelter->address->city,
                    "postal_code" => $pet->shelter->address->postal_code,
                ] : null,
            ] : null,
            "tags" => $pet->tags->map(fn(Tag $tag): array => [
                "id" => $tag->id,
                "name" => $tag->name,
            ])->toArray(),
        ];
    }
}
