<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Pet;
use Illuminate\Http\Resources\Json\JsonResource;

class PetIndexResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Pet $pet */
        $pet = $this->resource;

        return [
            "id" => $pet->id,
            "name" => $pet->name,
            "species" => $pet->species,
            "breed" => $pet->breed,
            "sex" => $pet->sex,
            "age" => $pet->age,
            "admission_date" => $pet->admission_date?->format("Y-m-d"),
            "size" => $pet->size,
            "weight" => $pet->weight,
            "color" => $pet->color,
            "description" => $pet->description,
            "behavioral_notes" => $pet->behavioral_notes,
            "adoption_status" => $pet->adoption_status,
            "shelter_city" => optional($pet->shelter?->address)->city,
            "shelter_postal_code" => optional($pet->shelter?->address)->postal_code,
            "tags" => $pet->tags->pluck("name")->values(),
        ];
    }
}
