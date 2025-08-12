<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "species" => $this->species,
            "breed" => $this->breed,
            "sex" => $this->sex,
            "age" => $this->age,
            "size" => $this->size,
            "weight" => $this->weight,
            "color" => $this->color,
            "sterilized" => $this->sterilized,
            "description" => $this->description,
            "health_status" => $this->health_status,
            "current_treatment" => $this->current_treatment,
            "vaccinated" => $this->vaccinated,
            "has_chip" => $this->has_chip,
            "chip_number" => $this->chip_number,
            "dewormed" => $this->dewormed,
            "deflea_treated" => $this->deflea_treated,
            "medical_tests" => $this->medical_tests,
            "food_type" => $this->food_type,
            "attitude_to_people" => $this->attitude_to_people,
            "attitude_to_dogs" => $this->attitude_to_dogs,
            "attitude_to_cats" => $this->attitude_to_cats,
            "attitude_to_children" => $this->attitude_to_children,
            "activity_level" => $this->activity_level,
            "behavioral_notes" => $this->behavioral_notes,
            "admission_date" => $this->admission_date ? $this->admission_date->toDateString() : null,
            "quarantine_end_date" => $this->quarantine_end_date ? $this->quarantine_end_date->toDateString() : null,
            "found_location" => $this->found_location,
            "adoption_status" => $this->adoption_status,
            "created_at" => $this->created_at->toDateTimeString(),
            "updated_at" => $this->updated_at->toDateTimeString(),
        ];
    }
}
