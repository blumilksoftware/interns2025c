<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetShelter extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'description',
        'address_id',
    ];

    public function address()
    {
        return $this->belongsTo(PetShelterAddress::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
