<?php

declare(strict_types=1);

namespace App\Http\Integrations\Connectors;

use Saloon\Http\Connector;

class CrawlerConnector extends Connector
{
    protected string $baseUrl = "";

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }
}
