<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAdminResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "role" => $user->role,
            "email_verified_at" => $user->email_verified_at?->toISOString(),
            "created_at" => $user->created_at?->toISOString(),
            "updated_at" => $user->updated_at?->toISOString(),
            "locale" => $user->locale,
        ];
    }
}
