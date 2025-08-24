<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PetShelter;
use Illuminate\Support\Facades\Log;

class PetShelterService
{
    public function store(array $results): void
    {
        if (!isset($results["shelters"]) || !is_array($results["shelters"])) {
            Log::warning("No shelters data found in the results to store.");

            return;
        }

        foreach ($results["shelters"] as $shelterData) {
            if (!is_array($shelterData)) {
                continue;
            }

            $name = $shelterData["name"] ?? null;
            $phone = $shelterData["phone"] ?? null;

            if (!$name) {
                continue;
            }

            $shelter = PetShelter::firstOrNew([
                "name" => $name,
                "phone" => $phone,
            ]);

            $shelter->email = $shelterData["email"] ?? $shelter->email;
            $shelter->description = $shelterData["description"] ?? $shelter->description;
            $shelter->url = $shelterData["url"] ?? $shelter->url;

            if ($shelter->exists || $shelter->isDirty()) {
                $shelter->save();
            }

            $addressPayload = [
                "address" => $shelterData["address"] ?? null,
                "city" => $shelterData["city"] ?? null,
                "postal_code" => $shelterData["postal_code"] ?? null,
            ];

            if (!empty($shelterData["shelter_address"]) && is_array($shelterData["shelter_address"])) {
                $addressPayload = array_merge($addressPayload, [
                    "address" => $shelterData["shelter_address"]["street"] ?? $addressPayload["address"],
                    "city" => $shelterData["shelter_address"]["city"] ?? $addressPayload["city"],
                    "postal_code" => $shelterData["shelter_address"]["postal_code"] ?? $addressPayload["postal_code"],
                ]);
            }

            if (array_filter($addressPayload, fn($v) => $v !== null && $v !== "")) {
                $shelter->address()->update($addressPayload);
            }
        }
    }
}
