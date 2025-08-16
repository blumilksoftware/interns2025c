<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetShelterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $shelter = $this->resource;

        return [
            "id" => $shelter->id,
            "name" => $shelter->name,
            "phone" => $shelter->phone,
            "email" => $shelter->email,
            "description" => $shelter->description,
        ];
    }
}
