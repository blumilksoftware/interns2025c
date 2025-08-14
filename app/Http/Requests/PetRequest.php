<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\PetHealthStatus;
use App\Enums\PetSex;
use App\Enums\PetSpecies;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255"],
            "species" => [
                "required",
                "string",
                Rule::in(array_column(PetSpecies::cases(), "value")),
            ],
            "breed" => ["nullable", "string", "max:255"],
            "sex" => [
                "required",
                "string",
                Rule::in(array_column(PetSex::cases(), "value")),
            ],
            "age" => ["nullable", "string", "max:255"],
            "size" => ["nullable", "string", "max:255"],
            "weight" => ["nullable", "numeric", "min:0", "max:512"],
            "color" => ["nullable", "string", "max:255"],
            "sterilized" => ["nullable", "boolean"],
            "description" => ["required", "string", "max:8192"],
            "health_status" => [
                "nullable",
                "string",
                Rule::in(array_column(PetHealthStatus::cases(), "value")),
                "max:500",
            ],
            "current_treatment" => ["nullable", "string", "max:500"],
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
            "medical_tests" => ["nullable", "string", "max:512"],
            "food_type" => ["nullable", "string", "max:255"],
            "attitude_to_people" => ["nullable", "string", "max:255"],
            "attitude_to_dogs" => ["nullable", "string", "max:255"],
            "attitude_to_cats" => ["nullable", "string", "max:255"],
            "attitude_to_children" => ["nullable", "string", "max:255"],
            "activity_level" => ["nullable", "string", "max:255"],
            "behavioral_notes" => ["nullable", "string", "max:500"],
            "admission_date" => ["nullable", "date"],
            "quarantine_end_date" => ["nullable", "date"],
            "found_location" => ["nullable", "string", "max:255"],
            "adoption_status" => ["nullable", "string", "max:255"],
        ];
    }
}
