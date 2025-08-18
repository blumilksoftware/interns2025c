<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create("pet_shelters", function (Blueprint $table): void {
            $table->id();
            $table->string("name");
            $table->string("phone")->nullable();
            $table->string("email")->nullable();
            $table->longText("description")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("pet_shelters");
    }
};
