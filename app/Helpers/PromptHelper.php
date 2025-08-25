<?php

declare(strict_types=1);

namespace App\Helpers;

use InvalidArgumentException;

class PromptHelper
{
    public static function getPromptFromMarkdown(string $fileName): ?string
    {
        if (empty($fileName)) {
            throw new InvalidArgumentException("Prompt file name cannot be empty");
        }

        $promptPath = resource_path("prompts/" . $fileName);

        if (file_exists($promptPath)) {
            return file_get_contents($promptPath);
        }

        return null;
    }
}
