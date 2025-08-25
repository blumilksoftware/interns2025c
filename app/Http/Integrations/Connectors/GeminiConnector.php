<?php

declare(strict_types=1);

namespace App\Http\Integrations\Connectors;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\HasTimeout;

class GeminiConnector extends Connector
{
    use HasTimeout;

    protected int $connectTimeout = 60;
    protected int $requestTimeout = 240;
    protected string $key;
    protected string $model;
    protected string $base;

    public function __construct()
    {
        $this->key = config("services.gemini.key");
        $this->model = config("services.gemini.model");
        $this->base = rtrim(config("services.gemini.endpoint"), "/");
    }

    public function resolveBaseUrl(): string
    {
        return "{$this->base}/{$this->model}:generateContent";
    }

    protected function defaultHeaders(): array
    {
        return [
            "x-goog-api-key" => $this->key,
            "Content-Type" => "application/json",
        ];
    }
}
