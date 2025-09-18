<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\PetAdoptionStatus;
use App\Enums\PetHealthStatus;
use App\Enums\PetSex;
use App\Enums\PetSpecies;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class PetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255"],
            "adoption_url" => [
                "nullable",
                "string",
                "max:2048",
                "url",
                Rule::prohibitedIf($this->input("adoption_status") === PetAdoptionStatus::Adopted->value),
            ],
            "species" => [
                "required",
                "string",
                new Enum(PetSpecies::class),
            ],
            "breed" => ["nullable", "string", "max:255"],
            "sex" => [
                "required",
                "string",
                new Enum(PetSex::class),
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
                new Enum(PetHealthStatus::class),
                "max:500",
            ],
            "current_treatment" => ["nullable", "string", "max:1024"],
            "vaccinated" => ["nullable", "boolean"],
            "has_chip" => ["nullable", "boolean"],
            "chip_number" => [
                "nullable",
                "string",
                "max:255",
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
            "behavioral_notes" => ["nullable", "string", "max:512"],
            "admission_date" => ["nullable", "date"],
            "quarantine_end_date" => ["nullable", "date"],
            "found_location" => ["nullable", "string", "max:255"],
            "adoption_status" => ["nullable", "string", "max:255"],
            "image_urls" => ["nullable", "array"],
            "image_urls.*" => ["string", "max:2048", "url"],
            "shelter_id" => [
                "required",
                "integer",
                "exists:pet_shelters,id",
            ],
            "tags" => ["nullable", "array"],
        ];
    }
}
