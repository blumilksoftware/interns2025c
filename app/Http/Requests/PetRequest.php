<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255"],
            "species" => ["required", "string", "max:255"],
            "breed" => ["nullable", "string", "max:255"],
            "gender" => ["required", "string", "max:255"],
            "age" => ["nullable", "string", "max:255"],
            "size" => ["nullable", "string", "max:255"],
            "weight" => ["nullable", "numeric", "min:0", "max:500"],
            "color" => ["nullable", "string", "max:255"],
            "sterilized" => ["nullable", "boolean"],
            "description" => ["required", "string", "max:255"],

            "health_status" => ["nullable", "string", "max:255"],
            "current_treatment" => ["nullable", "string", "max:255"],
            "vaccinated" => ["nullable", "boolean"],
            "has_chip" => ["nullable", "boolean"],
            "chip_number" => [
                "nullable",
                "string",
                "max:255",
                "required_if:has_chip,true",
                "prohibited_unless:has_chip,true",
            ],
            "dewormed" => ["nullable", "boolean"],
            "deflea_treated" => ["nullable", "boolean"],
            "medical_tests" => ["nullable", "string", "max:255"],
            "food_type" => ["nullable", "string", "max:255"],

            "attitude_to_people" => ["nullable", "string", "max:255"],
            "attitude_to_dogs" => ["nullable", "string", "max:255"],
            "attitude_to_cats" => ["nullable", "string", "max:255"],
            "attitude_to_children" => ["nullable", "string", "max:255"],
            "activity_level" => ["nullable", "string", "max:255"],
            "behavioral_notes" => ["nullable", "string", "max:255"],

            "admission_date" => ["nullable", "date"],
            "quarantine_end_date" => ["nullable", "date"],
            "found_location" => ["nullable", "string", "max:255"],
            "adoption_status" => ["nullable", "string", "max:255"],
        ];
    }
}
