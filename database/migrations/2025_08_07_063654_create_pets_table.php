<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create("pets", function (Blueprint $table): void {
            $table->id();
            $table->string("name");
            $table->string("species");
            $table->string("breed")->nullable();
            $table->string("sex");
            $table->string("age")->nullable();
            $table->string("size")->nullable();
            $table->float("weight", 5, 2)->nullable();
            $table->string("color")->nullable();
            $table->boolean("sterilized")->nullable();
            $table->text("description");
            $table->string("health_status")->nullable();
            $table->text("current_treatment")->nullable();
            $table->boolean("vaccinated")->nullable();
            $table->boolean("has_chip")->nullable();
            $table->string("chip_number")->nullable();
            $table->boolean("dewormed")->nullable();
            $table->boolean("deflea_treated")->nullable();
            $table->text("medical_tests")->nullable()->comment();
            $table->string("food_type")->nullable();
            $table->string("attitude_to_people")->nullable();
            $table->string("attitude_to_dogs")->nullable();
            $table->string("attitude_to_cats")->nullable();
            $table->string("attitude_to_children")->nullable();
            $table->string("activity_level")->nullable();
            $table->text("behavioral_notes")->nullable();
            $table->date("admission_date")->nullable();
            $table->date("quarantine_end_date")->nullable();
            $table->string("found_location")->nullable();
            $table->string("adoption_status")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("pets");
    }
};
