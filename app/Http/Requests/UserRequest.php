<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "email",
                "max:255",
                Rule::unique("users", "email")->ignore($this->user?->id),
            ],
            "role" => [
                "required",
                "string",
                Rule::in(array_column(Role::cases(), "value")),
            ],
        ];
    }
}
