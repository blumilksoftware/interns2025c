<?php

declare(strict_types=1);

namespace App\Http\Integrations\Connectors;

use Saloon\Http\Connector;

abstract class CrawlerConnector extends Connector
{
    protected int $connectTimeout = 30;
    protected int $requestTimeout = 120;
    protected string $baseUrl = "";

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function defaultHeaders(): array
    {
        return [
            "Content-Type" => "application/json",
        ];
    }
}
