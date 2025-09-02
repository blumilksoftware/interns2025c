<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PetMatchResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "pet" => new PetIndexResource($this->resource["pet"]),
            "match" => $this->resource["match"],
        ];
    }
}
