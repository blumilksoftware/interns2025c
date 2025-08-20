<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can("delete", $this->route("user"));
    }

    public function rules(): array
    {
        return [
            "confirm_deletion" => ["required", "boolean", "accepted"],
        ];
    }
}
