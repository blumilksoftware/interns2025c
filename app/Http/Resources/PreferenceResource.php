<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PreferenceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "preferences" => $this->preferences ?? [],
            "address" => $this->address,
            "city" => $this->city,
            "postal_code" => $this->postal_code,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            "radius_in_km" => $this->radius_in_km,
        ];
    }
}
