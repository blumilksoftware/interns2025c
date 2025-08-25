<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\LlmConnectorInterface;
use App\Http\Integrations\Requests\PostGeminiRequest;
use JsonException;
use RuntimeException;

class GeminiService
{
    public function __construct(
        protected LlmConnectorInterface $connector,
    ) {}

    /**
     * @throws JsonException
     */
    public function generateContent(array $payload): array
    {
        $request = new PostGeminiRequest($payload);

        $response = $this->connector->send($request);

        return $response->json();
    }

    public function createGeminiPayload(string $prompt, string $payload): array
    {
        return [
            "contents" => [
                [
                    "parts" => [
                        ["text" => "Prompt: $prompt , Page content: $payload"],
                    ],
                ],
            ],
        ];
    }

    public function getGeminiResult(array $response): string
    {
        if (!isset($response["candidates"][0]["content"]["parts"][0]["text"])) {
            throw new RuntimeException("Invalid LLM response structure");
        }

        return $response["candidates"][0]["content"]["parts"][0]["text"];
    }
}
