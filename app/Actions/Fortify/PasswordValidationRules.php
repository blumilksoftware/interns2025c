<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    protected function passwordRules(): array
    {
        return ["required", "string", Password::default()->min(8)->mixedCase()->numbers()->symbols(), "confirmed"];
    }
}
