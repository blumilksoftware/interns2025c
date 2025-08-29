<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PetHealthStatus;
use App\Enums\PetSex;
use App\Enums\PetSpecies;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property ?string $url
 * @property PetSpecies $species
 * @property ?string $breed
 * @property PetSex $sex
 * @property ?string $age
 * @property ?string $size
 * @property ?float $weight
 * @property ?string $color
 * @property ?bool $sterilized
 * @property string $description
 * @property ?PetHealthStatus $health_status
 * @property ?string $current_treatment
 * @property ?bool $vaccinated
 * @property ?bool $has_chip
 * @property ?string $chip_number
 * @property ?bool $dewormed
 * @property ?bool $deflea_treated
 * @property ?string $medical_tests
 * @property ?string $food_type
 * @property ?string $attitude_to_people
 * @property ?string $attitude_to_dogs
 * @property ?string $attitude_to_cats
 * @property ?string $attitude_to_children
 * @property ?string $activity_level
 * @property ?string $behavioral_notes
 * @property ?Carbon $admission_date
 * @property ?string $found_location
 * @property ?string $adoption_status
 * @property bool $is_accepted
 * @property int $shelter_id
 */
class Pet extends Model
{
    use HasFactory;

    protected $guarded = [
        "is_accepted",
    ];    
    protected $casts = [
        "age" => "string",
        "admission_date" => "date",
    ];

    public function shelter(): BelongsTo
    {
        return $this->belongsTo(PetShelter::class);
    }
}
