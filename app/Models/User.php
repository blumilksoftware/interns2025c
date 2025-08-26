<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        "name",
        "email",
        "password",
        "role",
    ];
    protected $hidden = [
        "password",
        "remember_token",
    ];

    public function hasShelterRole(): bool
    {
        return $this->role === Role::ShelterEmployee->value;
    }

    public function hasAdminRole(): bool
    {
        return $this->role === Role::Admin->value;
    }

    public function petShelters(): BelongsToMany
    {
        return $this->belongsToMany(PetShelter::class);
    }

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }
}
