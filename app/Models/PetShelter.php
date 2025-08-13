<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PetShelter extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "phone",
        "email",
        "description",
    ];

    public function address()
    {
        return $this->hasOne(PetShelterAddress::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
