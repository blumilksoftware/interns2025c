<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PetShelterAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        "address",
        "city",
        "postal_code",
    ];

    public function petShelter(): HasOne
    {
        return $this->hasOne(PetShelter::class);
    }
}
