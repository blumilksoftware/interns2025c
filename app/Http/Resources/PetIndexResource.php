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
            "behavioral_notes" => $pet->behavioral_notes,
            "adoption_status" => $pet->adoption_status,
        ];
    }
}
