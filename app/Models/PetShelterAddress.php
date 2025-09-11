<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetShelterAddress extends Model
{
    protected $fillable = [
        "pet_shelter_id",
        "address",
        "city",
        "postal_code",
        "latitude",
        "longitude",
    ];

    public function petShelter(): BelongsTo
    {
        return $this->belongsTo(PetShelter::class, "pet_shelter_id");
    }
}
