<?php

declare(strict_types=1);

namespace App\Http\Integrations\Connectors;

use App\Contracts\LlmConnectorInterface;
use Saloon\Traits\Plugins\HasTimeout;

class GeminiConnector extends CrawlerConnector implements LlmConnectorInterface
{
    use HasTimeout;

    protected string $key;
    protected string $model;
    protected string $base;

    public function __construct()
    {
        $this->key = config("services.llm.key");
        $this->model = config("services.llm.model");
        $this->base = rtrim(config("services.llm.endpoint"), "/");
    }

    public function resolveBaseUrl(): string
    {
        return "{$this->base}/{$this->model}:generateContent";
    }

    public function defaultHeaders(): array
    {
        return array_merge(parent::defaultHeaders(), [
            "x-goog-api-key" => $this->key,
        ]);
    }
}
