<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\PetShelter;
use App\Services\GeocodingService;
use Illuminate\Support\Arr;

class CreatePetShelterAction
{
    public function __construct(
        protected GeocodingService $geocodingService,
    ) {}

    public function execute(array $data): PetShelter
    {
        $shelterData = Arr::only($data, ["name", "phone", "email", "description", "url"]);
        $shelter = PetShelter::query()->create($shelterData);

        $addressData = Arr::only($data, ["address", "city", "postal_code"]);
        $address = $addressData["address"] ?? null;
        $city = $addressData["city"] ?? null;
        $postalCode = $addressData["postal_code"] ?? null;

        if ($address || $city || $postalCode) {
            $shelterAddress = $shelter->address()->create($addressData);

            $coords = $this->geocodingService->resolve($address, $city, $postalCode);

            if ($coords) {
                $shelterAddress->latitude = $coords["latitude"];
                $shelterAddress->longitude = $coords["longitude"];
                $shelterAddress->save();
            }
        }

        return $shelter;
    }
}
