<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Preference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Utils\TestUtils;

class PreferenceTest extends TestCase
{
    use RefreshDatabase;
    use TestUtils;

    public function testPreferencesIndexCanBeRendered(): void
    {
        [$user] = $this->createUserWithPreference();

        $this->actingAs($user)
            ->get("/dashboard")
            ->assertStatus(200)
            ->assertSee("Dashboard");
    }

    public function testPreferenceCanBeCreated(): void
    {
        $user = $this->createUser();
        $data = $this->preferenceData();

        $this->actingAs($user)
            ->post("/preferences", $data)
            ->assertRedirect();

        $this->assertDatabaseCount("preferences", 1);
        $this->assertDatabaseHas("preferences", ["user_id" => $user->id]);
    }

    public function testPreferenceCannotBeCreatedWithoutPreferencesField(): void
    {
        $user = $this->createUser();
        $data = [];

        $this->actingAs($user)
            ->post("/preferences", $data)
            ->assertSessionHasErrors(["preferences"]);

        $this->assertDatabaseCount("preferences", 0);
    }

    public function testPreferenceCanBeUpdated(): void
    {
        [$user, $preference] = $this->createUserWithPreference();
        $data = $this->preferenceData();

        $this->actingAs($user)
            ->put("/preferences/{$preference->id}", $data)
            ->assertRedirect();

        $this->assertDatabaseHas("preferences", ["id" => $preference->id, "user_id" => $user->id]);
    }

    public function testPreferenceCannotBeUpdatedByOtherUser(): void
    {
        [$user, $preference] = $this->createUserWithPreference();
        $otherUser = $this->createUser();
        $data = $this->preferenceData();

        $this->actingAs($otherUser)
            ->put("/preferences/{$preference->id}", $data)
            ->assertStatus(403);
    }

    public function testPreferenceCanBeDeleted(): void
    {
        [$user, $preference] = $this->createUserWithPreference();

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
        $user = $this->createUser();
        $data = $this->preferenceData(["preferences" => ["species" => [["value" => "dog", "weight" => -5]]]]);

        $this->actingAs($user)
            ->post("/preferences", $data)
            ->assertSessionHasErrors(["preferences.species.0.weight"]);
    }

    public function testPreferenceCannotBeCreatedWithInvalidBoolean(): void
    {
        $user = $this->createUser();
        $data = $this->preferenceData(["preferences" => ["vaccinated" => ["value" => "not_a_boolean"]]]);

        $this->actingAs($user)
            ->post("/preferences", $data)
            ->assertSessionHasErrors(["preferences.vaccinated.value"]);
    }

    public function testPreferenceCannotBeCreatedWithInvalidEnum(): void
    {
        $user = $this->createUser();
        $data = $this->preferenceData(["preferences" => ["species" => [["value" => "invalid_species", "weight" => 1]]]]);

        $this->actingAs($user)
            ->post("/preferences", $data)
            ->assertSessionHasErrors(["preferences.species.0.value"]);
    }
}
