<?php

declare(strict_types=1);

use App\Models\PetShelter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create("pet_shelter_addresses", function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(PetShelter::class)->constrained()->cascadeOnDelete();
            $table->string("address")->nullable();
            $table->string("city")->nullable();
            $table->string("postal_code")->nullable();
            $table->decimal("latitude", 10, 7)->nullable();
            $table->decimal("longitude", 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("pet_shelter_addresses");
    }
};
