<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PetTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function userWithoutProperRoleCannotCreatePet(): void
    {
        $user = User::factory()->create([
            "role" => Role::USER->value,
        ]);

        $pet = Pet::factory()->make([
            "name" => "TestPet",
            "species" => "dog",
        ])->toArray();

        $response = $this->actingAs($user)->post("/pets", $pet);

        $response->assertStatus(403);
        $this->assertDatabaseMissing("pets", ["name" => "TestPet", "species" => "dog"]);
    }

    #[Test]
    public function cannotCreatePetWithNameLongerThanExpected(): void
    {
        $randomNameThatExceedRequestLimit = Str::random(256);

        $pet = Pet::factory()->make([
            "name" => $randomNameThatExceedRequestLimit,
        ])->toArray();

        $response = $this->post("/pets", $pet);

        $response->assertSessionHasErrors(["name"]);
        $this->assertDatabaseMissing("pets", ["name" => $randomNameThatExceedRequestLimit]);
    }

    #[Test]
    public function cannotCreatePetWithInvalidNameType(): void
    {
        $numericName = 12345;
        $pet = Pet::factory()->make([
            "name" => $numericName,
        ])->toArray();

        $response = $this->post("/pets", $pet);

        $response->assertSessionHasErrors(["name"]);
        $this->assertDatabaseMissing("pets", ["name" => $numericName]);
    }

    #[Test]
    public function cannotCreatePetWithMissingRequiredFields(): void
    {
        $pet = [
            "species" => "dog",
            "description" => "",
        ];

        $response = $this->post("/pets", $pet);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(["name", "sex", "description"]);
    }

    #[Test]
    public function userWithoutProperRoleCannotUpdatePet(): void
    {
        $user = User::factory()->create([
            "role" => Role::USER->value,
        ]);

        $pet = Pet::factory()->create([
            "name" => "OldName",
        ]);

        $updateData = [
            "name" => "NewName",
        ];

        $this->actingAs($user)->put("/pets/{$pet->id}", $updateData);

        $this->assertNotEquals($pet->name, $updateData["name"]);
    }

    #[Test]
    public function cannotUpdatePetWithInvalidData(): void
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

    #[Test]
    public function userWithoutShelterRoleCannotDeletePet(): void
    {
        $pet = Pet::factory()->create();
        $user = User::factory()->create([
            "role" => Role::USER->value,
        ]);

        $response = $this->actingAs($user)->delete("/pets/{$pet->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas("pets", ["id" => $pet->id]);
    }

    #[Test]
    public function userWithShelterOrAdminRoleCanDeletePet(): void
    {
        $roles = [Role::SHELTER->value, Role::ADMIN->value];

        foreach ($roles as $role) {
            $pet = Pet::factory()->create();
            $user = User::factory()->create(["role" => $role]);

            $response = $this->actingAs($user)->delete("/pets/{$pet->id}");

            $response->assertStatus(302);
            $this->assertDatabaseMissing("pets", ["id" => $pet->id]);
        }
    }

    #[Test]
    public function deletingNonExistentPetReturnsNotFound(): void
    {
        $response = $this->delete("/pets/999999");

        $response->assertStatus(404);
    }

    #[Test]
    public function showingNonExistentPetReturnsNotFound(): void
    {
        $response = $this->get("/pets/999999");

        $response->assertStatus(404);
    }
}
