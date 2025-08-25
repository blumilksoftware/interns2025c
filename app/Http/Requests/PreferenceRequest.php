<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\PetAdoptionStatus;
use App\Enums\PetAge;
use App\Enums\PetAttitudeToCats;
use App\Enums\PetAttitudeToChildren;
use App\Enums\PetAttitudeToDogs;
use App\Enums\PetAttitudeToPeople;
use App\Enums\PetHealthStatus;
use App\Enums\PetSex;
use App\Enums\PetSize;
use App\Enums\PetSpecies;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PreferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "preferences" => "required|array",
            "preferences.species" => "sometimes|array",
            "preferences.species.*.value" => ["string", Rule::in(PetSpecies::values())],
            "preferences.species.*.weight" => "integer|min:0",
            "preferences.breed" => "sometimes|array",
            "preferences.breed.*.value" => "string",
            "preferences.breed.*.weight" => "integer|min:0",
            "preferences.sex" => "sometimes|array",
            "preferences.sex.*.value" => ["string", Rule::in(PetSex::values())],
            "preferences.sex.*.weight" => "integer|min:0",
            "preferences.size" => "sometimes|array",
            "preferences.size.*.value" => ["string", Rule::in(PetSize::values())],
            "preferences.size.*.weight" => "integer|min:0",
            "preferences.age" => "sometimes|array",
            "preferences.age.*.value" => ["string", Rule::in(PetAge::values())],
            "preferences.age.*.weight" => "integer|min:0",
            "preferences.health_status" => "sometimes|array",
            "preferences.health_status.*.value" => ["string", Rule::in(PetHealthStatus::values())],
            "preferences.health_status.*.weight" => "integer|min:0",
            "preferences.vaccinated" => "sometimes|array",
            "preferences.vaccinated.value" => "boolean",
            "preferences.vaccinated.weight" => "integer|min:0",
            "preferences.sterilized" => "sometimes|array",
            "preferences.sterilized.value" => "boolean",
            "preferences.sterilized.weight" => "integer|min:0",
            "preferences.has_chip" => "sometimes|array",
            "preferences.has_chip.value" => "boolean",
            "preferences.has_chip.weight" => "integer|min:0",
            "preferences.dewormed" => "sometimes|array",
            "preferences.dewormed.value" => "boolean",
            "preferences.dewormed.weight" => "integer|min:0",
            "preferences.deflea_treated" => "sometimes|array",
            "preferences.deflea_treated.value" => "boolean",
            "preferences.deflea_treated.weight" => "integer|min:0",
            "preferences.adoption_status" => "sometimes|array",
            "preferences.adoption_status.*.value" => ["string", Rule::in(PetAdoptionStatus::values())],
            "preferences.adoption_status.*.weight" => "integer|min:0",
            "preferences.attitude_to_people" => "sometimes|array",
            "preferences.attitude_to_people.*.value" => ["string", Rule::in(PetAttitudeToPeople::values())],
            "preferences.attitude_to_people.*.weight" => "integer|min:0",
            "preferences.attitude_to_dogs" => "sometimes|array",
            "preferences.attitude_to_dogs.*.value" => ["string", Rule::in(PetAttitudeToDogs::values())],
            "preferences.attitude_to_dogs.*.weight" => "integer|min:0",
            "preferences.attitude_to_cats" => "sometimes|array",
            "preferences.attitude_to_cats.*.value" => ["string", Rule::in(PetAttitudeToCats::values())],
            "preferences.attitude_to_cats.*.weight" => "integer|min:0",
            "preferences.attitude_to_children" => "sometimes|array",
            "preferences.attitude_to_children.*.value" => ["string", Rule::in(PetAttitudeToChildren::values())],
            "preferences.attitude_to_children.*.weight" => "integer|min:0",
            "preferences.tags" => "sometimes|array",
            "preferences.tags.*.value" => "string",
            "preferences.tags.*.weight" => "integer|min:0",
        ];
    }
}
