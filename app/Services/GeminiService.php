<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GeminiService
{
    protected $key;
    protected $model;
    protected $base;

    public function __construct()
    {
        $this->key = config("services.gemini.key");
        $this->model = config("services.gemini.model");
        $this->base = rtrim(config("services.gemini.endpoint"), "/");
    }

    public function generateContent(array $payload, $attempts = 3)
    {
        $url = "{$this->base}/{$this->model}:generateContent";

        $response = Http::retry($attempts, 100, fn($exception, $request) => $exception instanceof ConnectionException
            || ($exception->getCode() >= 500 && $exception->getCode() < 600))
            ->withHeaders([
                "x-goog-api-key" => $this->key,
                "Content-Type" => "application/json",
            ])
            ->timeout(120)
            ->post($url, $payload);

        if ($response->failed()) {
            Log::channel("single")->error("Gemini API failed", [
                "status" => $response->status(),
                "body" => $response->body(),
            ]);

            throw new RuntimeException("Gemini API error: " . $response->body());
        }

        return $response->json();
    }
}
