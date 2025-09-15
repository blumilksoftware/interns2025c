<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Integrations\Connectors\NominatimConnector;
use App\Http\Integrations\Requests\GeocodeRequest;
use Illuminate\Database\Eloquent\Model;

class GeocodingService
{
    public function resolve(string $address, ?string $city = null, ?string $postalCode = null): ?array
    {
        $query = trim(implode(", ", array_filter([$address, $city, $postalCode])));

        if ($query === "") {
            return null;
        }

        $connector = new NominatimConnector();
        $request = new GeocodeRequest($query);

        $response = $connector->send($request);

        $data = $response->json();

        if (empty($data[0])) {
            return null;
        }

        return [
            "latitude" => (float)$data[0]["lat"],
            "longitude" => (float)$data[0]["lon"],
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
