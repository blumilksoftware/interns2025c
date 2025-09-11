<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Role;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
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
 * @property string $locale
 * @property ?Carbon $email_verified_at
 * @property ?string $remember_token
 * @property-read Collection|array<PetShelter> $petShelters
 * @property-read Collection|array<Preference> $preferences
 */
class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        "name",
        "email",
        "password",
        "role",
        "locale",
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

    public function preferredLocale(): string
    {
        return $this->locale ?? config("app.locale");
    }

    public function petShelters(): BelongsToMany
    {
        return $this->belongsToMany(PetShelter::class);
    }

    public function preferences(): HasMany
    {
        return $this->hasMany(Preference::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification());
    }

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }
}
