<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\PetShelter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PetShelterTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePetShelterSuccessfully(): void
    {
        $user = User::factory()->create(["role" => "admin"]);
        $this->actingAs($user);

        $response = $this->post("/pet-shelters", [
            "name" => "Happy Paws",
            "phone" => "+123 456 7890",
            "email" => "contact@happypaws.org",
            "description" => "A very nice place for pets.",
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas("pet_shelters", [
            "name" => "Happy Paws",
            "phone" => "+123 456 7890",
            "email" => "contact@happypaws.org",
            "description" => "A very nice place for pets.",
        ]);
    }

    public function testCreatePetShelterUnauthorized(): void
    {
        $user = User::factory()->create(["role" => "user"]);
        $this->actingAs($user);

        $response = $this->post("/pet-shelters", [
            "name" => "Happy Paws",
            "phone" => "+123 456 7890",
            "email" => "contact@happypaws.org",
            "description" => "A very nice place for pets.",
        ]);

        $response->assertStatus(403);
    }

    public function testCreatePetShelterValidationFails(): void
    {
        $user = User::factory()->create(["role" => "admin"]);
        $this->actingAs($user);

        $response = $this->post("/pet-shelters", [
            "name" => "",
            "phone" => "invalid-phone-!!!",
            "email" => "not-an-email",
            "description" => "",
        ]);

        $response->assertSessionHasErrors(["name", "phone", "email", "description"]);
    }

    public function testUpdatePetShelterSuccessfully(): void
    {
        $user = User::factory()->create(["role" => "admin"]);
        $this->actingAs($user);

        $shelter = PetShelter::factory()->create();

        $response = $this->put("/pet-shelters/{$shelter->id}", [
            "name" => "New Name Shelter",
            "phone" => "+987 654 3210",
            "email" => "newemail@shelter.org",
            "description" => "Updated description.",
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas("pet_shelters", [
            "id" => $shelter->id,
            "name" => "New Name Shelter",
            "phone" => "+987 654 3210",
            "email" => "newemail@shelter.org",
            "description" => "Updated description.",
        ]);
    }

    public function testUpdatePetShelterUnauthorized(): void
    {
        $user = User::factory()->create(["role" => "user"]);
        $this->actingAs($user);

        $shelter = PetShelter::factory()->create();

        $response = $this->put("/pet-shelters/{$shelter->id}", [
            "name" => "New Name Shelter",
            "phone" => "+987 654 3210",
            "email" => "newemail@shelter.org",
            "description" => "Updated description.",
        ]);

        $response->assertStatus(403);
    }

    public function testUpdatePetShelterValidationFails(): void
    {
        $user = User::factory()->create(["role" => "admin"]);
        $this->actingAs($user);

        $shelter = PetShelter::factory()->create();

        $response = $this->put("/pet-shelters/{$shelter->id}", [
            "name" => "",
            "phone" => "!!!invalid!!!",
            "email" => "bad-email",
            "description" => "",
        ]);

        $response->assertSessionHasErrors(["name", "phone", "email", "description"]);
    }

    public function testDeletePetShelter(): void
    {
        $user = User::factory()->create(["role" => "admin"]);
        $this->actingAs($user);

        $shelter = PetShelter::factory()->create();

        $response = $this->delete("/pet-shelters/{$shelter->id}");

        $response->assertRedirect();
        $this->assertSoftDeleted("pet_shelters", ["id" => $shelter->id]);
    }

    public function testDeletePetShelterUnauthorized(): void
    {
        $user = User::factory()->create(["role" => "user"]);
        $this->actingAs($user);

        $shelter = PetShelter::factory()->create();

        $response = $this->delete("/pet-shelters/{$shelter->id}");

        $response->assertStatus(403);
    }

    public function testUpdatePetShelterAddressSuccessfully(): void
    {
        $user = User::factory()->create(["role" => "admin"]);
        $this->actingAs($user);

        $shelter = PetShelter::factory()->create();
        $address = $shelter->address;

        $response = $this->put("/pet-shelter-addresses/{$address->id}", [
            "address" => "456 New Street",
            "city" => "Newtown",
            "postal_code" => "98765-432",
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas("pet_shelter_addresses", [
            "id" => $address->id,
            "pet_shelter_id" => $shelter->id,
            "address" => "456 New Street",
            "city" => "Newtown",
            "postal_code" => "98765-432",
        ]);
    }

    public function testUpdatePetShelterAddressValidationFails(): void
    {
        $user = User::factory()->create(["role" => "admin"]);
        $this->actingAs($user);

        $shelter = PetShelter::factory()->create();
        $address = $shelter->address;

        $response = $this->put("/pet-shelter-addresses/{$address->id}", [
            "address" => Str::random(600),
            "city" => Str::random(200),
            "postal_code" => "!@#$%^&*()",
        ]);

        $response->assertSessionHasErrors(["address", "city", "postal_code"]);
    }

    public function testDeletingAddressNullifiesFields(): void
    {
        $user = User::factory()->create(["role" => "admin"]);
        $this->actingAs($user);

        $shelter = PetShelter::factory()->create();
        $address = $shelter->address;

        $this->delete("/pet-shelter-addresses/{$address->id}");

        $this->assertDatabaseHas("pet_shelter_addresses", [
            "id" => $address->id,
            "address" => null,
            "city" => null,
            "postal_code" => null,
        ]);

        $this->assertDatabaseHas("pet_shelters", ["id" => $shelter->id]);
    }

    public function testDeletingShelterDoesNotDeleteAddress(): void
    {
        $user = User::factory()->create(["role" => "admin"]);
        $this->actingAs($user);

        $shelter = PetShelter::factory()->create();
        $address = $shelter->address;

        $this->delete("/pet-shelters/{$shelter->id}");

        $this->assertSoftDeleted("pet_shelters", ["id" => $shelter->id]);
        $this->assertDatabaseHas("pet_shelter_addresses", ["id" => $address->id]);
    }

    public function testThatNewShelterAutomaticallyCreatesAddress(): void
    {
        $user = User::factory()->create(["role" => "admin"]);
        $this->actingAs($user);

        $response = $this->post("/pet-shelters", [
            "name" => "Shelter with Address",
            "phone" => "+123 456 7890",
            "email" => "example@example.com",
            "description" => "This shelter should have an address created automatically.",
            "address" => "123 Main St",
            "city" => "Cityville",
            "postal_code" => "12345",
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas("pet_shelter_addresses", [
            "address" => "123 Main St",
            "city" => "Cityville",
            "postal_code" => "12345",
        ]);
    }
}
