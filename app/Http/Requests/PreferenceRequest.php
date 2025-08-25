<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreferenceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => [
                "required",
                "string",
                "max:255",
                "unique:preferences,name,NULL,id,user_id," . $this->user()->id,
            ],
        ];
    }
}
