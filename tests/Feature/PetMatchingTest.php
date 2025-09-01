<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\Tag;
use App\Models\Preference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PetMatchingTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestIsRedirected(): void
    {
        $this->get('/dashboard/matches')->assertRedirect('/login');
    }

    public function testPetsAreReturnedWithMatch(): void
    {
        $user = User::factory()->create();
        Preference::factory()->for($user)->create([
            'preferences' => [
                'species' => [['value' => 'dog', 'weight' => 1]],
            ],
        ]);

        $dog = Pet::factory()->create(['species' => 'dog']);
        $cat = Pet::factory()->create(['species' => 'cat']);

        $dogTag = Tag::factory()->create();
        $catTag = Tag::factory()->create();

        $dog->tags()->syncWithoutDetaching([$dogTag->id]);
        $cat->tags()->syncWithoutDetaching([$catTag->id]);

        $response = $this->actingAs($user)->get('/dashboard/matches');

        $response->assertInertia(fn (Assert $page) =>
            $page->component('Dashboard/Dashboard')
                 ->has('pets', 2)
                 ->where('pets.0.pet.id', $dog->id)
                 ->where('pets.0.match', 100)
                 ->where('pets.1.pet.id', $cat->id)
                 ->where('pets.1.match', 0)
        );
    }

    public function testPetsAreSortedByMatchDescending(): void
    {
        $user = User::factory()->create();
        Preference::factory()->for($user)->create([
            'preferences' => [
                'species' => [['value' => 'dog', 'weight' => 1]],
            ],
        ]);

        $cat = Pet::factory()->create(['species' => 'cat']);
        $dog1 = Pet::factory()->create(['species' => 'dog']);
        $dog2 = Pet::factory()->create(['species' => 'dog']);

        $catTag = Tag::factory()->create();
        $dogTag1 = Tag::factory()->create();
        $dogTag2 = Tag::factory()->create();

        $cat->tags()->syncWithoutDetaching([$catTag->id]);
        $dog1->tags()->syncWithoutDetaching([$dogTag1->id]);
        $dog2->tags()->syncWithoutDetaching([$dogTag2->id]);

        $response = $this->actingAs($user)->get('/dashboard/matches');

        $response->assertInertia(fn (Assert $page) =>
            $page->component('Dashboard/Dashboard')
                 ->has('pets', 3)
        );

        $petsData = $response->inertiaProps()['pets'];
        $matches = collect($petsData)->pluck('match')->all();
        $sortedMatches = $matches;
        rsort($sortedMatches);

        $this->assertSame($sortedMatches, $matches, "Pets are not sorted by match descending");
    }

    public function testNoPreferenceReturnsZeroMatch(): void
    {
        $user = User::factory()->create();
        $pet1 = Pet::factory()->create();
        $pet2 = Pet::factory()->create();
        $pet3 = Pet::factory()->create();

        $tag1 = Tag::factory()->create();
        $tag2 = Tag::factory()->create();
        $tag3 = Tag::factory()->create();

        $pet1->tags()->syncWithoutDetaching([$tag1->id]);
        $pet2->tags()->syncWithoutDetaching([$tag2->id]);
        $pet3->tags()->syncWithoutDetaching([$tag3->id]);

        $response = $this->actingAs($user)->get('/dashboard/matches');

        $response->assertInertia(fn (Assert $page) =>
            $page->component('Dashboard/Dashboard')
                 ->has('pets', 3)
        );

        $petsData = $response->inertiaProps()['pets'];

        foreach ($petsData as $pet) {
            $this->assertSame(0, $pet['match']);
        }
    }

    public function testTagPreferenceAffectsMatch(): void
    {
        $user = User::factory()->create();
        Preference::factory()->for($user)->create([
            'preferences' => [
                'tags' => [['value' => 'friendly', 'weight' => 1]],
            ],
        ]);

        $petWithTag = Pet::factory()->create();
        $petWithoutTag = Pet::factory()->create();

        $friendlyTag = Tag::factory()->create(['name' => 'friendly']);
        $shyTag = Tag::factory()->create(['name' => 'shy']);

        $petWithTag->tags()->syncWithoutDetaching([$friendlyTag->id]);
        $petWithoutTag->tags()->syncWithoutDetaching([$shyTag->id]);

        $response = $this->actingAs($user)->get('/dashboard/matches');

        $petsData = $response->inertiaProps()['pets'];

        $matches = collect($petsData)->pluck('match')->all();

        $this->assertGreaterThan($matches[1], $matches[0]);
    }
}
