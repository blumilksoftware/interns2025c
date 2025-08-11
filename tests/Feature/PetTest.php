<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanCreatePet(): void
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

    public function testCreatePetFailsWithMissingRequiredFields(): void
    {
        $data = [
            "species" => "dog",
            "description" => "",
        ];

        $response = $this->post("/pets", $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(["name", "gender", "description"]);
    }

    public function testUserCanUpdatePet(): void
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

    public function testUpdatePetFailsWithInvalidData(): void
    {
        $pet = Pet::factory()->create();

        $updateData = [
            "name" => "",
            "species" => "",
            "gender" => "",
            "description" => "",
        ];

        $response = $this->put("/pets/{$pet->id}", $updateData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(["name", "species", "gender", "description"]);
    }

    public function testUserCanDeletePet(): void
    {
        $pet = Pet::factory()->create();

        $response = $this->delete("/pets/{$pet->id}");

        $response->assertStatus(302);
        $response->assertRedirect(route("pets.index"));
        $this->assertDatabaseMissing("pets", ["id" => $pet->id]);
    }

    public function testDeletingNonExistentPetReturnsNotFound(): void
    {
        $response = $this->delete("/pets/999999");

        $response->assertStatus(404);
    }

    public function testShowingNonExistentPetReturnsNotFound(): void
    {
        $response = $this->get("/pets/999999");

        $response->assertStatus(404);
    }
}
