<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PetShelterAddressResource extends JsonResource
{
    public function toArray($request): array
    {
        $address = $this->resource;

        return [
            "address" => $address->address,
            "city" => $address->city,
            "postal_code" => $address->postal_code,
        ];
    }
}
