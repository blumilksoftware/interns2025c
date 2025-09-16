<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property ?array $preferences
 * @property ?string $city
 * @property ?float $latitude
 * @property ?float $longitude
 * @property int $radius_in_km
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
        "radius_in_km",
    ];
    protected $casts = [
        "preferences" => "array",
        "latitude" => "float",
        "longitude" => "float",
        "radius_in_km" => "integer",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
