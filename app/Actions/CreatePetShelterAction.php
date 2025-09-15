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

        if (!empty($addressData["address"]) || !empty($addressData["city"]) || !empty($addressData["postal_code"])) {
            $shelterAddress = $shelter->address()->create($addressData);
            $this->geocodingService->fillCoordinates($shelterAddress, $addressData);
            $shelterAddress->save();
        }

        return $shelter;
    }
}
