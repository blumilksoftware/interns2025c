<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Pet;
use App\Models\PetShelter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PetTest extends TestCase
{
    use RefreshDatabase;

    public function testUserWithProperRoleCanCreatePet(): void
    {
        $user = User::factory()->create([
            "role" => Role::Shelter->value,
        ]);
        $shelter = PetShelter::factory()->create();
        $user->petShelters()->attach($shelter->id);
        $petData = Pet::factory()->make()->toArray();

        $response = $this->actingAs($user)->post("/pets", $petData);

        $response->assertStatus(302);
        $this->assertDatabaseHas("pets", [
            "name" => $petData["name"],
        ]);
    }

    public function testUserWithoutProperRoleCannotCreatePet(): void
    {
        $user = User::factory()->create([
            "role" => Role::User->value,
        ]);

        $pet = Pet::factory()->make([
            "name" => "TestPet",
            "species" => "dog",
        ])->toArray();

        $response = $this->actingAs($user)->post("/pets", $pet);

        $response->assertStatus(403);
        $this->assertDatabaseMissing("pets", ["name" => "TestPet", "species" => "dog"]);
    }

    public function testCannotCreatePetWithNameLongerThanExpected(): void
    {
        $randomNameThatExceedRequestLimit = Str::random(256);

        $pet = Pet::factory()->make([
            "name" => $randomNameThatExceedRequestLimit,
        ])->toArray();

        $response = $this->post("/pets", $pet);

        $response->assertSessionHasErrors(["name"]);
        $this->assertDatabaseMissing("pets", ["name" => $randomNameThatExceedRequestLimit]);
    }

    public function testCannotCreatePetWithInvalidNameType(): void
    {
        $numericName = 12345;
        $pet = Pet::factory()->make([
            "name" => $numericName,
        ])->toArray();

        $response = $this->post("/pets", $pet);

        $response->assertSessionHasErrors(["name"]);
        $this->assertDatabaseMissing("pets", ["name" => $numericName]);
    }

    public function testCannotCreatePetWithMissingRequiredFields(): void
    {
        $pet = [
            "species" => "dog",
            "description" => "",
        ];

        $response = $this->post("/pets", $pet);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(["name", "sex", "description"]);
    }

    public function testUserWithProperRoleCanUpdatePet(): void
    {
        $user = User::factory()->create([
            "role" => Role::Shelter->value,
        ]);
        $shelter = PetShelter::factory()->create();
        $user->petShelters()->attach($shelter->id);

        $pet = Pet::factory()->create([
            "name" => "OldName",
        ]);

        $updateData = [
            "name" => "NewName",
            "species" => $pet->species,
            "sex" => $pet->sex,
            "description" => $pet->description,
            "shelter_id" => $pet->shelter_id,
        ];

        $response = $this->actingAs($user)->put("/pets/{$pet->id}", $updateData);

        $response->assertStatus(302);
        $pet->refresh();
        $this->assertEquals("NewName", $pet->name);
    }

    public function testUserWithoutProperRoleCannotUpdatePet(): void
    {
        $user = User::factory()->create([
            "role" => Role::User->value,
        ]);

        $pet = Pet::factory()->create([
            "name" => "OldName",
        ]);

        $updateData = [
            "name" => "NewName",
        ];

        $this->actingAs($user)->put("/pets/{$pet->id}", $updateData);

        $pet->refresh();
        $this->assertEquals("OldName", $pet->name);
    }

    public function testCannotUpdatePetWithInvalidData(): void
    {
        $pet = Pet::factory()->create();

        $updateData = [
            "name" => "",
            "species" => "",
            "sex" => "",
            "description" => "",
        ];

        $response = $this->put("/pets/{$pet->id}", $updateData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(["name", "species", "sex", "description"]);
    }

    public function testUserWithShelterOrAdminRoleCanDeletePet(): void
    {
        $roles = [Role::Shelter->value, Role::Admin->value];

        foreach ($roles as $role) {
            $pet = Pet::factory()->create();
            $user = User::factory()->create(["role" => $role]);

            $response = $this->actingAs($user)->delete("/pets/{$pet->id}");

            $response->assertStatus(302);
            $this->assertDatabaseMissing("pets", ["id" => $pet->id]);
        }
    }

    public function testUserWithoutShelterRoleCannotDeletePet(): void
    {
        $pet = Pet::factory()->create();
        $user = User::factory()->create([
            "role" => Role::User->value,
        ]);

        $response = $this->actingAs($user)->delete("/pets/{$pet->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas("pets", ["id" => $pet->id]);
    }

    public function testDeletingNonExistentPetReturnsNotFound(): void
    {
        $response = $this->delete("/pets/999999");

        $response->assertStatus(404);
    }

    public function testShowingExistingPetReturnsSuccess(): void
    {
        $pet = Pet::factory()->create();

        $response = $this->get("/pets/{$pet->id}");

        $response->assertStatus(200);
    }

    public function testShowingNonExistentPetReturnsNotFound(): void
    {
        $response = $this->get("/pets/999999");

        $response->assertStatus(404);
    }
}
