<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\PetActivityLevel;
use App\Enums\PetAdoptionStatus;
use App\Enums\PetAge;
use App\Enums\PetAttitude;
use App\Enums\PetHealthStatus;
use App\Enums\PetSex;
use App\Enums\PetSize;
use App\Enums\PetSpecies;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PreferenceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "preferences" => ["required", "array"],
            "preferences.species" => ["sometimes", "array"],
            "preferences.species.*.value" => ["string", Rule::in(PetSpecies::values())],
            "preferences.species.*.weight" => ["integer", "min:0"],
            "preferences.breed" => ["sometimes", "array"],
            "preferences.breed.*.value" => ["string"],
            "preferences.breed.*.weight" => ["integer", "min:0"],
            "preferences.sex" => ["sometimes", "array"],
            "preferences.sex.*.value" => ["string", Rule::in(PetSex::values())],
            "preferences.sex.*.weight" => ["integer", "min:0"],
            "preferences.size" => ["sometimes", "array"],
            "preferences.size.*.value" => ["string", Rule::in(PetSize::values())],
            "preferences.size.*.weight" => ["integer", "min:0"],
            "preferences.age" => ["sometimes", "array"],
            "preferences.age.*.value" => ["string", Rule::in(PetAge::values())],
            "preferences.age.*.weight" => ["integer", "min:0"],
            "preferences.health_status" => ["sometimes", "array"],
            "preferences.health_status.*.value" => ["string", Rule::in(PetHealthStatus::values())],
            "preferences.health_status.*.weight" => ["integer", "min:0"],
            "preferences.vaccinated" => ["sometimes", "array"],
            "preferences.vaccinated.value" => ["boolean"],
            "preferences.vaccinated.weight" => ["integer", "min:0"],
            "preferences.sterilized" => ["sometimes", "array"],
            "preferences.sterilized.value" => ["boolean"],
            "preferences.sterilized.weight" => ["integer", "min:0"],
            "preferences.has_chip" => ["sometimes", "array"],
            "preferences.has_chip.value" => ["boolean"],
            "preferences.has_chip.weight" => ["integer", "min:0"],
            "preferences.dewormed" => ["sometimes", "array"],
            "preferences.dewormed.value" => ["boolean"],
            "preferences.dewormed.weight" => ["integer", "min:0"],
            "preferences.deflea_treated" => ["sometimes", "array"],
            "preferences.deflea_treated.value" => ["boolean"],
            "preferences.deflea_treated.weight" => ["integer", "min:0"],
            "preferences.adoption_status" => ["sometimes", "array"],
            "preferences.adoption_status.*.value" => ["string", Rule::in(PetAdoptionStatus::values())],
            "preferences.adoption_status.*.weight" => ["integer", "min:0"],
            "preferences.attitude" => ["sometimes", "array"],
            "preferences.attitude.*.value" => ["string", Rule::in(PetAttitude::values())],
            "preferences.attitude.*.weight" => ["integer", "min:0"],
            "preferences.activity_level" => ["sometimes", "array"],
            "preferences.activity_level.*.value" => ["string", Rule::in(PetActivityLevel::values())],
            "preferences.activity_level.*.weight" => ["integer", "min:0"],
            "preferences.tags" => ["sometimes", "array"],
            "preferences.tags.*.value" => ["string"],
            "preferences.tags.*.weight" => ["integer", "min:0"],
            'city' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'radius_km' => ['nullable', 'integer', 'min:1', 'max:1000'],
        ];
    }
}
