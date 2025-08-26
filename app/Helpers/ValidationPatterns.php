<?php

declare(strict_types=1);

namespace App\Helpers;

class ValidationPatterns
{
    public static function address(): array
    {
        return ["nullable", "string", "max:500"];
    }

    public static function city(): array
    {
        return ["nullable", "string", "max:100"];
    }

    public static function postalCode(): array
    {
        return ["nullable", "string", "max:20", 'regex:/^[0-9A-Za-z\s\-]+$/'];
    }
}
