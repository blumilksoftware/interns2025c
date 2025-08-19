<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetShelterAddressRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "address" => ["nullable", "string", "max:500"],
            "city" => ["nullable", "string", "max:100"],
            "postal_code" => ["nullable", "string", "max:20", 'regex:/^[0-9A-Za-z\s\-]+$/'],
        ];
    }
}
