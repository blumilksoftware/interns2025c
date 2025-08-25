<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property array $preferences
 * @property-read User $user
 */
class Preference extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "preferences"];
    protected $casts = [
        "preferences" => "array",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
