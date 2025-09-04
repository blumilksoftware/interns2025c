<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PetHealthStatus;
use App\Enums\PetSex;
use App\Enums\PetSpecies;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
 * @property ?string $adoption_url
 * @property ?array $image_urls
 * @property bool $is_accepted
 * @property int $shelter_id
 */
class Pet extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        "age" => "string",
        "admission_date" => "date",
        "image_urls" => "array",
    ];

    public function shelter(): BelongsTo
    {
        return $this->belongsTo(PetShelter::class);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $desiredWidth = 320;
        $desiredHeight = 320;
        $previewScaleFactor = 2;
        $this
            ->addMediaConversion("thumbnail")
            ->fit(Fit::Contain, $desiredWidth, $desiredHeight);
        $this
            ->addMediaConversion("preview")
            ->fit(Fit::Contain, $desiredWidth * $previewScaleFactor, $desiredHeight * $previewScaleFactor);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
