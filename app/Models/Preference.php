<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
