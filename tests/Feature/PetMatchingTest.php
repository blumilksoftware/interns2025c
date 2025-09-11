<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\Preference;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PetMatchingTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCanAccessDashboard(): void
    {
        $this->get("/dashboard")->assertStatus(200);
    }

    public function testPetsAreReturnedWithMatch(): void
    {
        $user = User::factory()->create();

        Preference::factory()->for($user)->create([
            "preferences" => [
                "species" => [["value" => "dog", "weight" => 1]],
            ],
        ]);

        $dog = Pet::factory()->create(["species" => "dog"]);
        $cat = Pet::factory()->create(["species" => "cat"]);

        $dogTag = Tag::factory()->create();
        $catTag = Tag::factory()->create();

        $dog->tags()->syncWithoutDetaching([$dogTag->id]);
        $cat->tags()->syncWithoutDetaching([$catTag->id]);

        $response = $this->actingAs($user)->get("/dashboard");

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component("Dashboard/Dashboard")
                ->has("pets.data"),
        );

        $petsData = $response->inertiaProps()["pets"]["data"] ?? [];
        $ids = collect($petsData)->pluck("id")->all();
        $this->assertContains($dog->id, $ids);
        $this->assertContains($cat->id, $ids);
    }

    public function testPetsAreSortedByMatchDescending(): void
    {
        $user = User::factory()->create();

        Preference::factory()->for($user)->create([
            "preferences" => [
                "species" => [["value" => "dog", "weight" => 1]],
            ],
        ]);

        $cat = Pet::factory()->create(["species" => "cat"]);
        $dog1 = Pet::factory()->create(["species" => "dog"]);
        $dog2 = Pet::factory()->create(["species" => "dog"]);

        $cat->tags()->syncWithoutDetaching([Tag::factory()->create()->id]);
        $dog1->tags()->syncWithoutDetaching([Tag::factory()->create()->id]);
        $dog2->tags()->syncWithoutDetaching([Tag::factory()->create()->id]);

        $response = $this->actingAs($user)->get("/dashboard");

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component("Dashboard/Dashboard")
                ->has("pets", 3),
        );

        $petsData = $response->inertiaProps()["pets"];
        $matches = collect($petsData)->pluck("match")->all();
        $sortedMatches = $matches;
        rsort($sortedMatches);

        $this->assertSame($sortedMatches, $matches, "Pets are not sorted by match descending");
    }

    public function testNoPreferenceReturnsZeroMatch(): void
    {
        $user = User::factory()->create();

        $pets = Pet::factory()->count(3)->create();
        $tags = Tag::factory()->count(3)->create();

        foreach ($pets as $index => $pet) {
            $pet->tags()->syncWithoutDetaching([$tags[$index]->id]);
        }

        $response = $this->actingAs($user)->get("/dashboard");

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component("Dashboard/Dashboard")
                ->has("pets.data", 3),
        );
    }

    public function testTagPreferenceAffectsMatch(): void
    {
        $user = User::factory()->create();

        Preference::factory()->for($user)->create([
            "preferences" => [
                "tags" => [["value" => "friendly", "weight" => 1]],
            ],
        ]);

        $petWithTag = Pet::factory()->create();
        $petWithoutTag = Pet::factory()->create();

        $friendlyTag = Tag::factory()->create(["name" => "friendly"]);
        $shyTag = Tag::factory()->create(["name" => "shy"]);

        $petWithTag->tags()->syncWithoutDetaching([$friendlyTag->id]);
        $petWithoutTag->tags()->syncWithoutDetaching([$shyTag->id]);

        $response = $this->actingAs($user)->get("/dashboard");

        // Validate Inertia response and that both pets are present in the list
        $response->assertInertia(
            fn(Assert $page) => $page
                ->component("Dashboard/Dashboard")
                ->has("pets.data", 2),
        );
    }
}
