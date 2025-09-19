<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FavouriteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "pet_id" => ["required", "exists:pets,id"],
        ];
    }
}
