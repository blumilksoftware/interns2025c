<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Pet;
use App\Models\Preference;
use App\Models\Tag;
use App\Services\PetMatcher;
use App\Services\ProximityService;
use Illuminate\Support\Collection;

class FindPetsForPreferenceAction
{
    public function __construct(
        protected ProximityService $proximity,
        protected PetMatcher $matcher,
    ) {}

    public function execute(?Preference $preference): Collection
    {
        $pets = Pet::query()->with("tags", "shelter.address");

        if ($preference && $preference->latitude && $preference->longitude) {
            $pets = $this->proximity->withinRadius(
                $preference->latitude,
                $preference->longitude,
                $preference->radius_in_km,
            );
        }

        return $pets->get()
            ->map(function (Pet $pet) use ($preference): array {
                $petData = $this->normalizePet($pet);

                $matchPercentage = $preference
                    ? $this->matcher->match($petData, $preference->preferences)
                    : 0;

                return [
                    "pet" => $pet,
                    "match" => $matchPercentage,
                ];
            })
            ->sortByDesc("match")
            ->values();
    }

    private function normalizePet(Pet $pet): array
    {
        return array_merge(
            $pet->toArray(),
            [
                "tags" => $pet->tags->map(fn(Tag $tag): array => [
                    "id" => $tag->id,
                    "name" => $tag->name,
                ])->all(),
                "shelter_latitude" => $pet->shelter?->address?->latitude,
                "shelter_longitude" => $pet->shelter?->address?->longitude,
            ],
        );
    }
}
