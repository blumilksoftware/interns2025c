<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\PetShelter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PetShelterTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePetShelterSuccessfully(): void
    {
        $response = $this->post("/pet-shelters", [
            "name" => "Happy Paws",
            "phone" => "+123 456 7890",
            "email" => "contact@happypaws.org",
            "description" => "A very nice place for pets.",
        ]);

        $response->assertRedirect("/admin");
        $this->assertDatabaseHas("pet_shelters", [
            "name" => "Happy Paws",
            "phone" => "+123 456 7890",
            "email" => "contact@happypaws.org",
            "description" => "A very nice place for pets.",
        ]);
    }

    public function testCreatePetShelterValidationFails(): void
    {
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
        $shelter = PetShelter::factory()->create();

        $response = $this->put("/pet-shelters/{$shelter->id}", [
            "name" => "New Name Shelter",
            "phone" => "+987 654 3210",
            "email" => "newemail@shelter.org",
            "description" => "Updated description.",
        ]);

        $response->assertRedirect("/admin");
        $this->assertDatabaseHas("pet_shelters", [
            "id" => $shelter->id,
            "name" => "New Name Shelter",
            "phone" => "+987 654 3210",
            "email" => "newemail@shelter.org",
            "description" => "Updated description.",
        ]);
    }

    public function testUpdatePetShelterValidationFails(): void
    {
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
        $shelter = PetShelter::factory()->create();

        $response = $this->delete("/pet-shelters/{$shelter->id}");

        $response->assertRedirect("/admin");
        $this->assertDatabaseMissing("pet_shelters", ["id" => $shelter->id]);
    }

    public function testUpdatePetShelterAddressSuccessfully(): void
    {
        $shelter = PetShelter::factory()->create();
        $address = $shelter->address;

        $response = $this->put("/pet-shelter-addresses/{$address->id}", [
            "address" => "456 New Street",
            "city" => "Newtown",
            "postal_code" => "98765-432",
        ]);

        $response->assertRedirect("/admin");
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

    public function testDeletingShelterCascadesOnAddress(): void
    {
        $shelter = PetShelter::factory()->create();
        $address = $shelter->address;

        $this->delete("/pet-shelters/{$shelter->id}");

        $this->assertDatabaseMissing("pet_shelters", ["id" => $shelter->id]);
        $this->assertDatabaseMissing("pet_shelter_addresses", ["id" => $address->id]);
    }

    public function testThatNewShelterAutomaticallyCreatesAddress(): void
    {
        $response = $this->post("/pet-shelters", [
            "name" => "Shelter with Address",
            "phone" => "+123 456 7890",
            "email" => "example@example.com",
            "description" => "This shelter should have an address created automatically.",
            "address" => "123 Main St",
            "city" => "Cityville",
            "postal_code" => "12345",
        ]);

        $response->assertRedirect("/admin");

        $this->assertDatabaseHas("pet_shelter_addresses", [
            "address" => "123 Main St",
            "city" => "Cityville",
            "postal_code" => "12345",
        ]);
    }
}
