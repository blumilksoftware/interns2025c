<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetMatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "pet" => new PetIndexResource($this->resource["pet"]),
            "match" => $this->resource["match"],
        ];
    }
}
