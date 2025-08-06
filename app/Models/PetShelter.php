<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetShelter extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "phone",
        "email",
        "description",
        "address_id",
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
