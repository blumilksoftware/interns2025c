<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property array|null $preferences
 * @property string|null $city
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int $radius_km
 * @property-read User $user
 */
class Preference extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "preferences",
        "city",
        "latitude",
        "longitude",
        "radius_km",
    ];

    protected $casts = [
        "preferences" => "array",
        "latitude"    => "float",
        "longitude"   => "float",
        "radius_km"   => "integer",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}