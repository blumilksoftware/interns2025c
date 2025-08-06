<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetShelterAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        "address",
        "city",
        "postal_code",
    ];

    public function petShelter()
    {
        return $this->hasOne(PetShelter::class);
    }
}
