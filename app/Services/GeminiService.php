<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Integrations\Connectors\GeminiConnector;
use App\Http\Integrations\Requests\PostGeminiRequest;
use Illuminate\Support\Facades\Log;
use JsonException;
use RuntimeException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class GeminiService
{
    /**
     * @throws JsonException
     */
    public function generateContent(array $payload): array
    {
        $connector = new GeminiConnector();
        $request = new PostGeminiRequest($payload);
        $response = null;

        try {
            $response = $connector->send($request);
        } catch (RequestException $e) {
            throw new RuntimeException(
                "Gemini API error: " . $e->getMessage(),
                $e->getCode(),
                $e,
            );
        } catch (FatalRequestException $e) {
            Log::error("Gemini API fatal error: " . $e->getMessage(), [
                "payload" => $payload,
                "exception" => $e,
            ]);
        }

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
        if (isset($response["candidates"][0]["content"]["parts"][0]["text"])) {
            return $response["candidates"][0]["content"]["parts"][0]["text"];
        }

        throw new RuntimeException("Invalid Gemini response structure");
    }
}
