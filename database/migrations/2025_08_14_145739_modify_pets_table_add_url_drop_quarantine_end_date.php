<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table("pets", function (Blueprint $table): void {
            $table->string("adoption_url")->nullable()->after("name");
        });
    }

    public function down(): void
    {
        Schema::table("pets", function (Blueprint $table): void {
            $table->dropColumn("adoption_url");
            $table->date("quarantine_end_date")->nullable()->after("admission_date");
        });
    }
};
