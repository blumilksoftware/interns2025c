<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\PetShelter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetShelterAdminResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var PetShelter $shelter */
        $shelter = $this->resource;

        $shelterAddress = $shelter->address;

        return [
            "id" => $shelter->id,
            "name" => $shelter->name,
            "phone" => $shelter->phone,
            "email" => $shelter->email,
            "description" => $shelter->description,
            "url" => $shelter->url,
            "address" => $shelterAddress->address,
            "city" => $shelterAddress->city,
            "postal_code" => $shelterAddress->postal_code,
            "created_at" => $shelter->created_at?->toISOString(),
            "updated_at" => $shelter->updated_at?->toISOString(),
            "deleted_at" => $shelter->deleted_at?->toISOString(),
        ];
    }
}
