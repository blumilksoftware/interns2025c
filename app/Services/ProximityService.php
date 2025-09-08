<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Pet;

class ProximityService
{
    public function withinRadius(
        Builder $query,
        float $latitude,
        float $longitude,
        float $radiusKm
    ): Builder {
        // Log input
        logger()->info('ProximityService input', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'radiusKm' => $radiusKm,
        ]);

        $sub = Pet::withoutGlobalScopes()->select('pets.*')
            ->join(
                'pet_shelter_addresses',
                'pets.shelter_id',
                '=',
                'pet_shelter_addresses.pet_shelter_id'
            )
            ->selectRaw(
                '6371 * acos(
                    cos(radians(?)) * cos(radians(pet_shelter_addresses.latitude)) *
                    cos(radians(pet_shelter_addresses.longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(pet_shelter_addresses.latitude))
                ) AS distance',
                [$latitude, $longitude, $latitude]
            )
            ->whereNotNull('pet_shelter_addresses.latitude')
            ->whereNotNull('pet_shelter_addresses.longitude');

        $queryWithDistance = Pet::withoutGlobalScopes()->fromSub($sub, 'pets_with_distance')
            ->where('distance', '<=', $radiusKm)
            ->orderBy('distance');

        // Log output SQL + bindings
        logger()->info('ProximityService output SQL', [
            'sql' => $queryWithDistance->toSql(),
            'bindings' => $queryWithDistance->getBindings(),
        ]);

        try {
            $clone = clone $queryWithDistance;
            $rows = $clone->get();
            logger()->info('ProximityService output Pets', $rows->map(function ($pet) {
                return [
                    'id' => $pet->id ?? null,
                    'name' => $pet->name ?? null,
                    'distance' => $pet->distance ?? null,
                ];
            })->toArray());
        } catch (\Throwable $e) {
            // don't break caller — just log the failure
            logger()->warning('ProximityService logging failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return $queryWithDistance;
    }
}
