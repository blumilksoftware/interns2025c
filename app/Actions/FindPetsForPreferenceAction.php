<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Pet;
use App\Models\Preference;
use App\Services\PetMatcher;
use App\Services\ProximityService;
use Illuminate\Support\Collection;

class FindPetsForPreferenceAction
{
    protected ProximityService $proximity;
    protected PetMatcher $matcher;

    public function __construct(ProximityService $proximity, PetMatcher $matcher)
    {
        $this->proximity = $proximity;
        $this->matcher = $matcher;
    }

    public function execute(?Preference $preference): Collection
    {
        // Start with eager-loading
        $pets = Pet::with('tags', 'shelter.address');

        // Apply proximity filter if preference has coordinates
        if ($preference && $preference->latitude && $preference->longitude) {
            // Use ProximityService directly on the main query
            $pets = $this->proximity->withinRadius(
                $pets,
                $preference->latitude,
                $preference->longitude,
                $preference->radius_km,
            );
        }

        return $pets->get()
            ->map(function (Pet $pet) use ($preference) {
                $petData = $this->normalizePet($pet);

                $matchPercentage = $preference
                    ? $this->matcher->match($petData, $preference->preferences)
                    : 0;

                return [
                    'pet' => $pet,
                    'match' => $matchPercentage,
                ];
            })
            ->sortByDesc('match')
            ->values();
    }


    private function normalizePet(Pet $pet): array
    {
        return [
            'id' => $pet->id,
            'species' => $pet->species,
            'tags' => $pet->tags->map(fn($t) => ['id' => $t->id, 'name' => $t->name])->all(),
            'age' => $pet->age,
            'size' => $pet->size,
            'shelter_id' => $pet->shelter_id,
            'shelter_latitude' => $pet->shelter?->address?->latitude,
            'shelter_longitude' => $pet->shelter?->address?->longitude,
        ];
    }
}
