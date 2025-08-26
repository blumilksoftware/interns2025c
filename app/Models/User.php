<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property-read Collection|array<PetShelter> $petShelters
 * @property-read Collection|array<Preference> $preferences
 */
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

    public function preferences(): HasMany
    {
        return $this->hasMany(Preference::class);
    }

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }
}
