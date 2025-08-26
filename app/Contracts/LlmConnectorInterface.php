<?php

declare(strict_types=1);

namespace App\Contracts;

use Saloon\Http\Request as SaloonRequest;
use Saloon\Http\Response as SaloonResponse;

interface LlmConnectorInterface
{
    public function send(SaloonRequest $request): SaloonResponse;

    public function defaultHeaders(): array;
}
