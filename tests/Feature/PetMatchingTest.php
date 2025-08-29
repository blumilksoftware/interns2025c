<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Pet;
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

        Pet::factory()->create(['species' => 'cat']);
        Pet::factory()->create(['species' => 'dog']);
        Pet::factory()->create(['species' => 'dog']);

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
        Pet::factory()->count(3)->create();

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
}
