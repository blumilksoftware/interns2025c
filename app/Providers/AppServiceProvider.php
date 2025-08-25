<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\LlmConnectorInterface;
use App\Http\Integrations\Connectors\GeminiConnector;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LlmConnectorInterface::class, GeminiConnector::class);
    }

    public function boot(): void
    {
    }
}
