<?php

declare(strict_types=1);

namespace App\Http\Integrations\Requests;

use Illuminate\Support\Facades\Cache;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetPageRequest extends Request implements Cacheable
{
    use HasCaching;

    protected Method $method = Method::GET;

    public function __construct(
        protected string $url,
    ) {}

    public function resolveEndpoint(): string
    {
        return $this->url;
    }

    public function resolveCacheDriver(): LaravelCacheDriver
    {
        return new LaravelCacheDriver(Cache::store(config("cache.default")));
    }

    public function cacheExpiryInSeconds(): int
    {
        return 7 * 3600;
    }
}
