<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn("pet_shelters", "deleted_at")) {
            Schema::table("pet_shelters", function (Blueprint $table): void {
                $table->softDeletes();
            });
        }

        if (!Schema::hasColumn("pets", "deleted_at")) {
            Schema::table("pets", function (Blueprint $table): void {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn("pets", "deleted_at")) {
            Schema::table("pets", function (Blueprint $table): void {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasColumn("pet_shelters", "deleted_at")) {
            Schema::table("pet_shelters", function (Blueprint $table): void {
                $table->dropSoftDeletes();
            });
        }
    }
};
