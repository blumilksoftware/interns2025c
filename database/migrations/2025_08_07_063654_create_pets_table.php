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

            // Basic Information
            $table->string("name");
            $table->string("species");
            $table->string("breed")->nullable();
            $table->string("gender");
            $table->boolean("sterilized")->default(false);
            $table->string("age")->nullable(); // Flexible format: "2 years", "6 months", or birth date
            $table->string("size")->nullable();
            $table->float("weight", 5, 2)->nullable();
            $table->string("color")->nullable();
            $table->text("description");

            // Health Records
            $table->string("health_status")->nullable();
            $table->text("current_treatment")->nullable();
            $table->boolean("vaccinated")->nullable()->default(false);
            $table->boolean("has_chip")->nullable()->default(false);
            $table->string("chip_number")->nullable();
            $table->boolean("dewormed")->nullable()->default(false);
            $table->boolean("deflea_treated")->nullable()->default(false);
            $table->string("medical_tests")->nullable(); // FIV/FeLV results, etc.
            $table->string("food_type")->nullable();

            // Behavioral Profile
            $table->string("attitude_to_people")->nullable();
            $table->string("attitude_to_dogs")->nullable();
            $table->string("attitude_to_cats")->nullable();
            $table->string("attitude_to_children")->nullable();
            $table->string("activity_level")->nullable();
            $table->text("behavioral_notes")->nullable();

            // Shelter Management
            $table->date("admission_date")->nullable();
            $table->date("quarantine_end_date")->nullable();
            $table->string("found_location")->nullable();
            $table->string("adoption_status")->nullable();

            $table->foreignId("shelter_id")->constrained("pet_shelters");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("pets");
    }
};
