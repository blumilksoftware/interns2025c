<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\PetShelter;
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
            "latitude" => null,
            "longitude" => null,
            "radius_in_km" => null,
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
            "latitude" => null,
            "longitude" => null,
            "radius_in_km" => null,
        ]);

        $cat = Pet::factory()->create(["species" => "cat"]);
        $dog1 = Pet::factory()->create(["species" => "dog"]);
        $dog2 = Pet::factory()->create(["species" => "dog"]);

        $cat->tags()->syncWithoutDetaching([Tag::factory()->create()->id]);
        $dog1->tags()->syncWithoutDetaching([Tag::factory()->create()->id]);
        $dog2->tags()->syncWithoutDetaching([Tag::factory()->create()->id]);

        $response = $this->actingAs($user)->get("/dashboard");

        $petsData = $response->inertiaProps()["pets"];
        $matches = collect($petsData)->pluck("match")->all();

        $sorted = $matches;
        rsort($sorted);

        $this->assertSame($sorted, $matches);
    }

    public function testNoPreferenceReturnsZeroMatch(): void
    {
        $user = User::factory()->create();

        Preference::factory()->for($user)->create([
            "preferences" => [],
            "latitude" => null,
            "longitude" => null,
            "radius_in_km" => null,
        ]);

        Pet::factory()->count(3)->create();

        $response = $this->actingAs($user)->get("/dashboard");

        $petsData = $response->inertiaProps()["pets"];

        foreach ($petsData as $petMatch) {
            $this->assertSame(0, $petMatch["match"]);
        }
    }

    public function testTagPreferenceAffectsMatch(): void
    {
        $user = User::factory()->create();

        Preference::factory()->for($user)->create([
            "preferences" => [
                "tags" => [["value" => "friendly", "weight" => 1]],
            ],
            "latitude" => null,
            "longitude" => null,
            "radius_in_km" => null,
        ]);

        $petWithTag = Pet::factory()->create();
        $petWithoutTag = Pet::factory()->create();

        $friendlyTag = Tag::factory()->create(["name" => "friendly"]);
        $shyTag = Tag::factory()->create(["name" => "shy"]);

        $petWithTag->tags()->syncWithoutDetaching([$friendlyTag->id]);
        $petWithoutTag->tags()->syncWithoutDetaching([$shyTag->id]);

        $response = $this->actingAs($user)->get("/dashboard");

        $petsData = $response->inertiaProps()["pets"];
        $matches = collect($petsData)->pluck("match")->all();

        $this->assertGreaterThan($matches[1], $matches[0]);
    }

    public function testPetsAreFilteredByProximity(): void
    {
        $user = User::factory()->create();

        Preference::factory()->for($user)->create([
            "preferences" => [],
            "latitude" => 40.0,
            "longitude" => -75.0,
            "radius_in_km" => 50,
        ]);

        $nearShelter = PetShelter::factory()->create();
        $nearShelter->address()->create([
            "latitude" => 40.1,
            "longitude" => -75.1,
        ]);
        $petNear = Pet::factory()->create();
        $petNear->shelter()->associate($nearShelter)->save();

        $farShelter = PetShelter::factory()->create();
        $farShelter->address()->create([
            "latitude" => 45.0,
            "longitude" => -80.0,
        ]);
        $petFar = Pet::factory()->create();
        $petFar->shelter()->associate($farShelter)->save();

        $response = $this->actingAs($user)->get("/dashboard/matches");

        $petsData = $response->inertiaProps()["pets"];
        $petIds = collect($petsData)->pluck("pet.data.id");

        $this->assertContains($petNear->id, $petIds);
        $this->assertNotContains($petFar->id, $petIds);
    }

    public function testPetsOutsideRadiusReturnZeroMatch(): void
    {
        $user = User::factory()->create();

        Preference::factory()->for($user)->create([
            "preferences" => [],
            "latitude" => 40.0,
            "longitude" => -75.0,
            "radius_in_km" => 1,
        ]);

        $farShelter = PetShelter::factory()->create();
        $farShelter->address()->create([
            "latitude" => 50.0,
            "longitude" => -80.0,
        ]);
        $petFar = Pet::factory()->create();
        $petFar->shelter()->associate($farShelter)->save();

        $response = $this->actingAs($user)->get("/dashboard/matches");

        $petsData = $response->inertiaProps()["pets"];
        $this->assertEmpty($petsData);
    }
}
