<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Pet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SavePetImagesFromUrls implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use InteractsWithQueue;
    use SerializesModels;

    public function __construct(
        public int $petId,
    ) {}

    public function handle(): void
    {
        $pet = Pet::query()->find($this->petId);

        if (!$pet) {
            Log::warning("Pet not found: {$this->petId}");

            return;
        }

        $petImageUrls = $pet->image_urls;

        if (!is_array($petImageUrls) || empty($petImageUrls)) {
            Log::info("No image URLs found for pet ID {$this->petId}");

            return;
        }

        foreach ($petImageUrls as $imageUrl) {
            if ($pet->media()->where("file_name", basename($imageUrl))->exists()) {
                continue;
            }

            try {
                $pet
                    ->addMediaFromUrl($imageUrl)
                    ->usingFileName($pet->name . "-" . basename($imageUrl))
                    ->toMediaCollection("pet_images");
            } catch (Throwable $exception) {
                Log::warning(
                    sprintf(
                        "Failed to save image from URL %s for pet ID %d: %s",
                        $imageUrl,
                        $this->petId,
                        $exception->getMessage(),
                    ),
                );
            }
        }
    }
}
