<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tag = $this->route("tag");

        return [
            "name" => [
                "required",
                "max:255",
                Rule::unique("tags")->ignore($tag),
            ],
        ];
    }
}
