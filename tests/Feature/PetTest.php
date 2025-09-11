<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Pet;
use App\Models\PetShelter;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class PetTest extends TestCase
{
    use RefreshDatabase;

    public function testUserWithProperRoleCanCreatePet(): void
    {
        $user = User::factory()->create([
            "role" => Role::ShelterEmployee->value,
        ]);
        $shelter = PetShelter::factory()->create();
        $user->petShelters()->attach($shelter->id);

        $petData = Pet::factory()->make()->toArray();
        $petData["shelter_id"] = $shelter->id;

        $response = $this->actingAs($user)->post("/pets", $petData);

        $response->assertStatus(302);
        $this->assertDatabaseHas("pets", [
            "name" => $petData["name"],
        ]);
    }

    public function testUserWithProperRoleCanCreatePetWithTags(): void
    {
        $user = User::factory()->create([
            "role" => Role::ShelterEmployee->value,
        ]);
        $shelter = PetShelter::factory()->create();
        $user->petShelters()->attach($shelter->id);

        $tags = Tag::factory()->count(3)->create();
        $petData = Pet::factory()->make()->toArray();
        $petData["shelter_id"] = $shelter->id;

        $response = $this->actingAs($user)->post("/pets", $petData);
        $response->assertStatus(302);

        $pet = Pet::where("name", $petData["name"])->firstOrFail();
        $pet->tags()->sync($tags->pluck("id"));

        $this->assertCount(3, $pet->tags);
        $this->assertEqualsCanonicalizing(
            $tags->pluck("id")->toArray(),
            $pet->tags->pluck("id")->toArray(),
        );
    }

    public function testUserWithoutProperRoleCannotCreatePet(): void
    {
        $user = User::factory()->create([
            "role" => Role::User->value,
        ]);

        $petData = Pet::factory()->make(["name" => "TestPet", "species" => "dog"])->toArray();

        $response = $this->actingAs($user)->post("/pets", $petData);
        $response->assertStatus(403);
        $this->assertDatabaseMissing("pets", ["name" => "TestPet"]);
    }

    public function testCannotCreatePetWithInvalidData(): void
    {
        $pet = ["species" => "dog", "description" => ""];

        $response = $this->post("/pets", $pet);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(["name", "sex", "description"]);
    }

    public function testUserWithProperRoleCanUpdatePet(): void
    {
        $user = User::factory()->create(["role" => Role::ShelterEmployee->value]);
        $shelter = PetShelter::factory()->create();
        $user->petShelters()->attach($shelter->id);

        $pet = Pet::factory()->create(["name" => "OldName"]);

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

    public function testShelterEmployeeCanUpdatePetTags(): void
    {
        $user = User::factory()->create(["role" => Role::ShelterEmployee->value]);
        $shelter = PetShelter::factory()->create();
        $user->petShelters()->attach($shelter->id);

        $pet = Pet::factory()->create();
        $initialTags = Tag::factory()->count(2)->create();
        $pet->tags()->attach($initialTags);

        $newTags = Tag::factory()->count(3)->create();
        $updateData = [
            "name" => $pet->name,
            "species" => $pet->species,
            "sex" => $pet->sex,
            "description" => $pet->description,
            "shelter_id" => $pet->shelter_id,
            "tags" => $newTags->pluck("name")->toArray(),
        ];

        $response = $this->actingAs($user)->put("/pets/{$pet->id}", $updateData);
        $response->assertStatus(302);

        $pet->refresh();
        $this->assertCount(3, $pet->tags);
        $this->assertEqualsCanonicalizing(
            $newTags->pluck("id")->toArray(),
            $pet->tags->pluck("id")->toArray(),
        );
    }

    public function testUserWithShelterOrAdminRoleCanDeletePet(): void
    {
        foreach ([Role::ShelterEmployee->value, Role::Admin->value] as $role) {
            $pet = Pet::factory()->create();
            $user = User::factory()->create(["role" => $role]);

            $response = $this->actingAs($user)->delete("/pets/{$pet->id}");
            $response->assertStatus(302);

            $this->assertSoftDeleted("pets", ["id" => $pet->id]);
        }
    }

    public function testDeletingPetRemovesTagRelations(): void
    {
        $user = User::factory()->create(["role" => Role::ShelterEmployee->value]);
        $shelter = PetShelter::factory()->create();
        $user->petShelters()->attach($shelter->id);

        $pet = Pet::factory()->create();
        $tags = Tag::factory()->count(2)->create();
        $pet->tags()->attach($tags);

        $response = $this->actingAs($user)->delete("/pets/{$pet->id}");
        $response->assertStatus(302);
        $this->assertSoftDeleted("pets", ["id" => $pet->id]);

        foreach ($tags as $tag) {
            $this->assertDatabaseMissing("pet_tag", [
                "pet_id" => $pet->id,
                "tag_id" => $tag->id,
            ]);
        }
    }

    public function testUserWithoutShelterRoleCannotDeletePet(): void
    {
        $pet = Pet::factory()->create();
        $user = User::factory()->create(["role" => Role::User->value]);

        $response = $this->actingAs($user)->delete("/pets/{$pet->id}");
        $response->assertStatus(403);
        $this->assertNotSoftDeleted("pets", ["id" => $pet->id]);
    }

    public function testShowingExistingPetReturnsSuccess(): void
    {
        $pet = Pet::factory()->create();
        $response = $this->get("/pets/{$pet->id}");
        $response->assertStatus(200);
    }

    public function testShowingPetIncludesTags(): void
    {
        $pet = Pet::factory()->create(
            ["is_accepted" => true],
        );
        $tags = Tag::factory()->count(2)->create();
        $pet->tags()->syncWithoutDetaching($tags->pluck("id")->toArray());

        $response = $this->get("/pets/{$pet->id}");

        $response->assertInertia(
            fn(AssertableInertia $page) => $page->component("Pets/Show")
                ->where("pet.data.id", $pet->id)
                ->has("pet.data.tags", 2)
                ->where("pet.data.tags.0.id", $tags[0]->id)
                ->where("pet.data.tags.1.id", $tags[1]->id),
        );
    }

    public function testShowingNonExistentPetReturnsNotFound(): void
    {
        $response = $this->get("/pets/999999");

        $response->assertStatus(404);
    }
}
