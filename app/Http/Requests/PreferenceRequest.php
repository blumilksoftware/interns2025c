<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PreferenceRequest extends FormRequest
{
    public function rules(): array
    {
        $preferenceId = $this->route("preference")?->id;

        return [
            "name" => [
                "required",
                "string",
                "max:255",
                Rule::unique("preferences")
                    ->ignore($preferenceId)
                    ->where(fn($query) => $query->where("user_id", $this->user()->id)),
            ],
        ];
    }
}
