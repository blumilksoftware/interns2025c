<?php

declare(strict_types=1);

namespace App\Http\Integrations\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetPageRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $url,
    ) {}

    public function resolveEndpoint(): string
    {
        return $this->url;
    }
}
