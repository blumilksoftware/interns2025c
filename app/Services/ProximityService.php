<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Pet;
use Illuminate\Database\Eloquent\Builder;

class ProximityService
{
    public function withinRadius(float $latitude, float $longitude, float $radiusKm): Builder
    {
        $sub = Pet::withoutGlobalScopes()->select("pets.*")
            ->join(
                "pet_shelter_addresses",
                "pets.shelter_id",
                "=",
                "pet_shelter_addresses.pet_shelter_id",
            )
            ->selectRaw(
                "6371 * acos(
                    cos(radians(?)) * cos(radians(pet_shelter_addresses.latitude)) *
                    cos(radians(pet_shelter_addresses.longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(pet_shelter_addresses.latitude))
                ) AS distance",
                [$latitude, $longitude, $latitude],
            )
            ->whereNotNull("pet_shelter_addresses.latitude")
            ->whereNotNull("pet_shelter_addresses.longitude");

        $queryWithDistance = Pet::withoutGlobalScopes()
            ->fromSub($sub, "pets_with_distance")
            ->where("distance", "<=", $radiusKm)
            ->orderBy("distance");

        return $queryWithDistance;
    }
}
