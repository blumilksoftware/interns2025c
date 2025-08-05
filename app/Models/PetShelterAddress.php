<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetShelterAddress extends Model
{
    protected $fillable = [
        'address',
        'city',
        'postal_code',
    ];

    public function petShelter()
    {
        return $this->hasOne(PetShelter::class);
    }
}
