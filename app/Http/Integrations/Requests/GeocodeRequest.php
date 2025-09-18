<?php

declare(strict_types=1);

namespace App\Http\Integrations\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GeocodeRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $address,
        protected ?string $city = null,
        protected ?string $postalCode = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/search";
    }

    public function defaultQuery(): array
    {
        $query = trim(implode(", ", array_filter([$this->address, $this->city, $this->postalCode])));

        return [
            "query" => $query,
            "format" => "json",
            "limit" => 1,
        ];
    }
}
