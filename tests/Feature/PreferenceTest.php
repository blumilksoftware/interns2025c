<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Preference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreferenceTest extends TestCase
{
    use RefreshDatabase;

    public function testPreferencesIndexCanBeRendered(): void
    {
        $user = User::factory()->create();
        Preference::factory()->for($user)->create();

        $this->actingAs($user)
            ->get("/preferences")
            ->assertStatus(200)
            ->assertSee("Dashboard");
    }

    public function testGuestsCannotAccessPreferencesIndex(): void
    {
        $this->get("/preferences")->assertRedirect("/login");
    }

    public function testPreferenceCanBeCreated(): void
    {
        $user = User::factory()->create();
        $data = Preference::factory()->make()->toArray();

        $this->actingAs($user)
            ->post("/preferences", $data)
            ->assertRedirect();

        $this->assertDatabaseCount("preferences", 1);
        $this->assertDatabaseHas("preferences", ["user_id" => $user->id]);
    }

    public function testPreferenceCannotBeCreatedWithoutPreferencesField(): void
    {
        $user = User::factory()->create();
        $data = [];

        $this->actingAs($user)
            ->post("/preferences", $data)
            ->assertSessionHasErrors(["preferences"]);

        $this->assertDatabaseCount("preferences", 0);
    }

    public function testPreferenceCanBeUpdated(): void
    {
        $user = User::factory()->create();
        $preference = Preference::factory()->for($user)->create();
        $data = $preference->preferences;

        if (isset($data["species"][0])) {
            $data["species"][0]["weight"] = 9;
        }

        $this->actingAs($user)
            ->put("/preferences/{$preference->id}", ["preferences" => $data])
            ->assertRedirect();

        $this->assertEquals(9, Preference::first()->preferences["species"][0]["weight"]);
    }

    public function testPreferenceCannotBeUpdatedWithInvalidEnum(): void
    {
        $user = User::factory()->create();
        $preference = Preference::factory()->for($user)->create();
        $data = $preference->preferences;

        if (isset($data["species"][0])) {
            $data["species"][0]["value"] = "invalid_species";
        }

        $this->actingAs($user)
            ->put("/preferences/{$preference->id}", ["preferences" => $data])
            ->assertSessionHasErrors(["preferences.species.0.value"]);
    }

    public function testPreferenceCanBeDeleted(): void
    {
        $user = User::factory()->create();
        $preference = Preference::factory()->for($user)->create();

        $this->actingAs($user)
            ->delete("/preferences/{$preference->id}")
            ->assertRedirect();

        $this->assertDatabaseCount("preferences", 0);
    }

    public function testGuestCannotDeletePreference(): void
    {
        $preference = Preference::factory()->create();

        $this->delete("/preferences/{$preference->id}")
            ->assertRedirect("/login");

        $this->assertDatabaseCount("preferences", 1);
    }

    public function testPreferenceCannotBeCreatedWithInvalidWeight(): void
    {
        $user = User::factory()->create();
        $data = Preference::factory()->make()->toArray();

        if (isset($data["preferences"]["species"][0])) {
            $data["preferences"]["species"][0]["weight"] = -5;
        }

        $this->actingAs($user)
            ->post("/preferences", $data)
            ->assertSessionHasErrors(["preferences.species.0.weight"]);
    }

    public function testPreferenceCannotBeCreatedWithInvalidBoolean(): void
    {
        $user = User::factory()->create();
        $data = Preference::factory()->make()->toArray();

        if (isset($data["preferences"]["vaccinated"])) {
            $data["preferences"]["vaccinated"]["value"] = "not_a_boolean";
        }

        $this->actingAs($user)
            ->post("/preferences", $data)
            ->assertSessionHasErrors(["preferences.vaccinated.value"]);
    }
}
