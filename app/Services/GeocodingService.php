<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Integrations\Connectors\NominatimConnector;
use App\Http\Integrations\Requests\GeocodeRequest;

class GeocodingService
{
    public function resolve(?string $address, ?string $city = null, ?string $postalCode = null): ?array
    {
        $queries = [
            trim(implode(", ", array_filter([$address, $city, $postalCode]))),
            trim(implode(", ", array_filter([$address, $city]))),
            trim(implode(", ", array_filter([$address, $postalCode]))),
            trim($address ?? ""),
            trim($city ?? ""),
            trim($postalCode ?? ""),
        ];

        $connector = new NominatimConnector();

        foreach ($queries as $query) {
            if ($query === "") {
                continue;
            }

            $request = new GeocodeRequest($query);
            $response = $connector->send($request);
            $data = $response->json();

            if (!empty($data[0]["lat"]) && !empty($data[0]["lon"])) {
                return [
                    "latitude" => (float)$data[0]["lat"],
                    "longitude" => (float)$data[0]["lon"],
                ];
            }
        }

        return null;
    }
}
