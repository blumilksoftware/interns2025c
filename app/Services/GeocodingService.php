<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class GeocodingService
{
    public function resolve(string $address, ?string $city = null, ?string $postalCode = null): ?array
    {
        $query = trim(implode(", ", array_filter([$address, $city, $postalCode])));

        if ($query === "") {
            return null;
        }

        $response = Http::get("https://nominatim.openstreetmap.org/search", [
            "q" => $query,
            "format" => "json",
            "limit" => 1,
        ]);

        if ($response->failed() || empty($response[0])) {
            return null;
        }

        return [
            "latitude" => (float)$response[0]["lat"],
            "longitude" => (float)$response[0]["lon"],
        ];
    }

    public function fillCoordinates(Model $model, array $addressFields): void
    {
        $coords = $this->resolve(
            $addressFields["address"] ?? null,
            $addressFields["city"] ?? null,
            $addressFields["postal_code"] ?? null,
        );

        if ($coords) {
            $model->fill([
                "latitude" => $coords["latitude"],
                "longitude" => $coords["longitude"],
            ]);
        }
    }
}
