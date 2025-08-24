<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PetShelter extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "phone",
        "email",
        "description",
        "url",
    ];

    public static function getAllPetShelterUrls(): array
    {
        return self::query()
            ->whereNotNull("url")
            ->pluck("url")
            ->filter()
            ->unique()
            ->toArray();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(PetShelterAddress::class);
    }

    protected static function booted(): void
    {
        static::created(function (PetShelter $shelter): void {
            if ($shelter->address()->doesntExist()) {
                $shelter->address()->create();
            }
        });
    }
}
