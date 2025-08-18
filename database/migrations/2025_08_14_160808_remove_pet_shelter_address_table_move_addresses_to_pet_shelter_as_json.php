<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::dropIfExists("pet_shelter_addresses");
        Schema::table("pet_shelters", function (Blueprint $table): void {
            $table->json("shelter_address")->nullable();
            $table->string("url")->nullable();
        });
    }

    public function down(): void
    {
        Schema::table("pet_shelters", function (Blueprint $table): void {
            $table->dropColumn("shelter_address");
            $table->dropColumn("shelter_url");
        });
        Schema::create("pet_shelter_address", function (Blueprint $table): void {
            $table->id();
            $table->string("address")->nullable();
            $table->string("city")->nullable();
            $table->string("postal_code")->nullable();
            $table->timestamps();
        });
    }
};
