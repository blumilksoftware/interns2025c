<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavouriteTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Pet $pet;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->pet = Pet::factory()->create();
    }

    public function testFavouriteCanBeCreated(): void
    {
        $this->actingAs($this->user)
            ->post("/favourites", ["pet_id" => $this->pet->id])
            ->assertRedirect();

        $this->assertDatabaseHas("favourites", [
            "user_id" => $this->user->id,
            "pet_id" => $this->pet->id,
        ]);
    }

    public function testFavouriteCannotBeCreatedTwice(): void
    {
        $this->user->favourites()->attach($this->pet->id);

        $this->actingAs($this->user)
            ->post("/favourites", ["pet_id" => $this->pet->id])
            ->assertStatus(403);

        $this->assertDatabaseCount("favourites", 1);
    }

    public function testFavouriteCannotBeCreatedByGuest(): void
    {
        $this->post("/favourites", ["pet_id" => $this->pet->id])
            ->assertRedirect("/login");

        $this->assertDatabaseCount("favourites", 0);
    }

    public function testFavouriteCanBeDeleted(): void
    {
        $this->user->favourites()->attach($this->pet->id);

        $this->actingAs($this->user)
            ->delete("/favourites/{$this->pet->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing("favourites", [
            "user_id" => $this->user->id,
            "pet_id" => $this->pet->id,
        ]);
    }

    public function testFavouriteCannotBeDeletedByGuest(): void
    {
        $this->user->favourites()->attach($this->pet->id);

        $this->delete("/favourites/{$this->pet->id}")
            ->assertRedirect("/login");

        $this->assertDatabaseHas("favourites", [
            "user_id" => $this->user->id,
            "pet_id" => $this->pet->id,
        ]);
    }

    public function testFavouriteCannotBeDeletedIfNotExists(): void
    {
        $otherPet = Pet::factory()->create();

        $this->actingAs($this->user)
            ->delete("/favourites/{$otherPet->id}")
            ->assertStatus(403);

        $this->assertDatabaseCount("favourites", 0);
    }
}
