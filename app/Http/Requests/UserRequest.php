<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = $this->route("user")?->id;

        return [
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "email", "max:255", "unique:users,email," . $userId],
            "password" => $this->isMethod("POST") 
                ? ["required", "string", "min:8"] 
                : ["nullable", "string", "min:8"],
        ];
    }
}
