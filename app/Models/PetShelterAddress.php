<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetShelterAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        "pet_shelter_id",
        "address",
        "city",
        "postal_code",
    ];

    public function shelter()
    {
        return $this->belongsTo(PetShelter::class, "pet_shelter_id");
    }
}
