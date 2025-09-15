<?php

declare(strict_types=1);

namespace App\Http\Integrations\Connectors;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\HasTimeout;

class NominatimConnector extends Connector
{
    use HasTimeout;

    public function resolveBaseUrl(): string
    {
        return "https://nominatim.openstreetmap.org";
    }

    public function defaultHeaders(): array
    {
        return [
            "User-Agent" => config("app.name") . " geocoding-client",
        ];
    }

    public function defaultQuery(): array
    {
        return [
            "format" => "json",
            "limit" => 1,
        ];
    }
}
