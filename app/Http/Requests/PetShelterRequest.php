<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Utils\ValidationPatterns;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PetShelterRequest extends FormRequest
{
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
            "address" => ValidationPatterns::address(),
            "city" => ValidationPatterns::city(),
            "postal_code" => ValidationPatterns::postalCode(),
        ];
    }
}
