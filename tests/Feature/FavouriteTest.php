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

    public function testFavouritesIndexCanBeRendered(): void
    {
        $user = User::factory()->create();
        $pets = Pet::factory()->count(2)->create();
        $user->favourites()->attach($pets->pluck("id")->toArray());

        $this->actingAs($user)
            ->get("/favourites")
            ->assertStatus(200)
            ->assertSee($pets->first()->name);
    }

    public function testGuestsCannotAccessFavouritesIndex(): void
    {
        $this->get("/favourites")->assertRedirect("/login");
    }

    public function testFavouriteCanBeCreated(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create();

        $this->actingAs($user)
            ->post("/favourites/{$pet->id}")
            ->assertRedirect();

        $this->assertDatabaseHas("favourites", ["user_id" => $user->id, "pet_id" => $pet->id]);
    }

    public function testFavouriteCannotBeCreatedByGuest(): void
    {
        $pet = Pet::factory()->create();

        $this->post("/favourites/{$pet->id}")->assertRedirect("/login");

        $this->assertDatabaseCount("favourites", 0);
    }

    public function testFavouriteCannotBeCreatedTwice(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create();
        $user->favourites()->attach($pet->id);

        $this->actingAs($user)
            ->post("/favourites/{$pet->id}")
            ->assertRedirect();

        $this->assertDatabaseCount("favourites", 1);
    }

    public function testFavouriteCanBeDeleted(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create();
        $user->favourites()->attach($pet->id);

        $this->actingAs($user)
            ->delete("/favourites/{$pet->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing("favourites", ["user_id" => $user->id, "pet_id" => $pet->id]);
    }

    public function testFavouriteCannotBeDeletedByGuest(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create();
        $user->favourites()->attach($pet->id);

        $this->delete("/favourites/{$pet->id}")->assertRedirect("/login");

        $this->assertDatabaseHas("favourites", ["user_id" => $user->id, "pet_id" => $pet->id]);
    }

    public function testFavouriteCannotBeDeletedIfNotExists(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create();

        $this->actingAs($user)
            ->delete("/favourites/{$pet->id}")
            ->assertRedirect();

        $this->assertDatabaseCount("favourites", 0);
    }
}
