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
            'name' => ['required', 'string', 'max:255'],
            'species' => ['required', 'string', 'max:255'],
            'breed' => ['nullable', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:255'],
            'age' => ['nullable', 'string', 'max:255'],
            'size' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'numeric'],
            'color' => ['nullable', 'string', 'max:255'],
            'sterilized' => ['nullable', 'boolean'],
            'description' => ['required', 'string'],

            'health_status' => ['nullable', 'string'],
            'current_treatment' => ['nullable', 'string'],
            'vaccinated' => ['nullable', 'boolean'],
            'has_chip' => ['nullable', 'boolean'],
            'chip_number' => ['nullable', 'string', 'required_if:has_chip,true', 'prohibited_unless:has_chip,true'],
            'dewormed' => ['nullable', 'boolean'],
            'deflea_treated' => ['nullable', 'boolean'],
            'medical_tests' => ['nullable', 'string'],
            'food_type' => ['nullable', 'string'],

            'attitude_to_people' => ['nullable', 'string'],
            'attitude_to_dogs' => ['nullable', 'string'],
            'attitude_to_cats' => ['nullable', 'string'],
            'attitude_to_children' => ['nullable', 'string'],
            'activity_level' => ['nullable', 'string'],
            'behavioral_notes' => ['nullable', 'string'],

            'admission_date' => ['nullable', 'date'],
            'quarantine_end_date' => ['nullable', 'date'],
            'found_location' => ['nullable', 'string'],
            'adoption_status' => ['nullable', 'string'],

            // 'shelter_id' => ['required', 'exists:pet_shelters,id'],
        ];
    }
}
