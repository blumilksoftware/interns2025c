<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PetShelterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255"],
            "phone" => [
                "nullable",
                "string",
                "max:20",
                'regex:/^\+?[0-9\s\-]+$/',
                Rule::unique("pet_shelters", "phone")->ignore($this->pet_shelter?->id),
            ],
            "email" => [
                "nullable",
                "email",
                "max:255",
                Rule::unique("pet_shelters", "email")->ignore($this->pet_shelter?->id),
            ],
            "description" => ["required", "string"],
            "address" => ["nullable", "string", "max:500"],
            "city" => ["nullable", "string", "max:100"],
            "postal_code" => ["nullable", "string", "max:20", 'regex:/^[0-9A-Za-z\s\-]+$/'],
        ];
    }
}
