<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PetShelter extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "phone",
        "email",
        "description",
        "shelter_address",
        "shelter_url",
    ];
    protected $casts = [
        "shelter_address" => "array",
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class);
    }
}
