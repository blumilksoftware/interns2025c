<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table("pet_shelters", function (Blueprint $table): void {
            $table->string("url")->nullable();
        });
    }

    public function down(): void
    {
        Schema::table("pet_shelters", function (Blueprint $table): void {
            $table->dropColumn("url");
        });
    }
};
