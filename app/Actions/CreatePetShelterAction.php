<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\PetShelter;
use Illuminate\Support\Arr;

class CreatePetShelterAction
{
    public function execute(array $data): PetShelter
    {
        $shelterData = Arr::only($data, [
            "name",
            "phone",
            "email",
            "description",
            "url",
        ]);

        $shelter = PetShelter::query()->create($shelterData);
        $addressData = Arr::only($data, ["address", "city", "postal_code"]);

        if (!empty($addressData["address"])) {
            $shelter->address()->update($addressData);
        }

        return $shelter;
    }
}
