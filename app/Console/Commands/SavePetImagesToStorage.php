<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\SavePetImagesFromUrls;
use App\Models\Pet;
use Exception;
use Illuminate\Console\Command;

class SavePetImagesToStorage extends Command
{
    protected $signature = "pets:images";
    protected $description = "Save pet images from URLs to storage";

    public function handle(): void
    {
        $petsWithImageUrls = Pet::query()
            ->where("is_accepted", true)
            ->whereNotNull("image_urls")
            ->get();

        $petsWithImageUrls->each(function (Pet $pet): void {
            try {
                SavePetImagesFromUrls::dispatch($pet->id);
                $this->info("Saving images for pet ID {$pet->id}");
            } catch (Exception $exception) {
                $this->error("Failed to save image for pet ID {$pet->id}: " . $exception->getMessage());
            }
        });
    }
}
