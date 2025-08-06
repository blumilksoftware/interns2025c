<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tagId = $this->route('id');

        return [
            'name' => [
                'required',
                'max:255',
                'unique:tags,name' . ($tagId ? ',' . $tagId : ''),
            ],
        ];
    }
}
