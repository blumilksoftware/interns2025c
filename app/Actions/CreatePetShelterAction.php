<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\PetShelter;
use Illuminate\Support\Arr;

class CreatePetShelterAction
{
    public function execute(array $data): PetShelter
    {
        $shelter = PetShelter::query()->create($data);
        $addressData = Arr::only($data, ["address", "city", "postal_code"]);

        if (!empty($addressData["address"])) {
            $shelter->address()->create($addressData);
        }

        return $shelter;
    }
}
