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
        ];

        $response = $this->post("/pets", $data);

        $response->assertStatus(302);
        $response->assertRedirect(route("pets.index"));
        $this->assertDatabaseHas("pets", ["name" => "Buddy"]);
    }

    public function testUpdatePetUpdatesPet(): void
    {
        $pet = Pet::factory()->create([
            "name" => "OldName",
            "species" => "cat",
            "gender" => "female",
            "description" => "Calm cat",
        ]);

        $updateData = [
            "name" => "NewName",
            "species" => "cat",
            "gender" => "female",
            "description" => "Very calm cat",
        ];

        $response = $this->put("/pets/{$pet->id}", $updateData);

        $response->assertStatus(302);
        $response->assertRedirect(route("pets.index"));
        $this->assertDatabaseHas("pets", ["name" => "NewName"]);
    }

    public function testDeletePetDeletesPet(): void
    {
        $pet = Pet::factory()->create();

        $response = $this->delete("/pets/{$pet->id}");

        $response->assertStatus(302);
        $response->assertRedirect(route("pets.index"));
        $this->assertDatabaseMissing("pets", ["id" => $pet->id]);
    }
}
