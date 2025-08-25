<?php

declare(strict_types=1);

namespace App\Http\Integrations\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class PostGeminiRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $payload = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return "";
    }

    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
