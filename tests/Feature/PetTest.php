<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetTest extends TestCase
{
    use RefreshDatabase;

    public function testStorePetCreatesPet(): void
    {
        $data = [
            "name" => "Buddy",
            "species" => "dog",
            "gender" => "male",
            "description" => "Friendly dog",
            // 'shelter_id' => 1,
        ];

        $response = $this->post("/pets", $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas("pets", ["name" => "Buddy"]);
    }

    public function testUpdatePetUpdatesPet(): void
    {
        $pet = Pet::factory()->create([
            "name" => "OldName",
            "species" => "cat",
            "gender" => "female",
            "description" => "Calm cat",
            // 'shelter_id' => 1,
        ]);

        $updateData = [
            "name" => "NewName",
            "species" => "cat",
            "gender" => "female",
            "description" => "Very calm cat",
            // 'shelter_id' => 2,
        ];

        $response = $this->put("/pets/{$pet->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas("pets", ["name" => "NewName"]);
    }

    public function testDeletePetDeletesPet(): void
    {
        $pet = Pet::factory()->create();

        $response = $this->delete("/pets/{$pet->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing("pets", ["id" => $pet->id]);
    }
}
