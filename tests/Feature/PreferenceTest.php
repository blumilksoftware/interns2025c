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

    public function testUserCanCreatePreference(): void
    {
        $user = User::factory()->create();

        $preferenceData = Preference::factory()->make([
            "user_id" => $user->id,
        ])->toArray();

        $response = $this->actingAs($user)->post("/preferences", $preferenceData);

        $response->assertStatus(302);
        $this->assertDatabaseHas("preferences", [
            "user_id" => $user->id,
            "name" => $preferenceData["name"],
        ]);
    }

    public function testUserCannotCreateDuplicatePreference(): void
    {
        $user = User::factory()->create();

        $preference = Preference::factory()->create([
            "user_id" => $user->id,
        ]);

        $duplicateData = Preference::factory()->make([
            "name" => $preference->name,
        ])->toArray();

        $response = $this->actingAs($user)->post("/preferences", $duplicateData);

        $response->assertSessionHasErrors(["name"]);

        $this->assertCount(
            1,
            Preference::where("user_id", $user->id)->get(),
        );
    }

    public function testUserCanUpdatePreference(): void
    {
        $user = User::factory()->create();
        $preference = Preference::factory()->create([
            "user_id" => $user->id,
            "name" => "Old Name",
        ]);

        $updateData = ["name" => "New Name"];

        $response = $this->actingAs($user)->put("/preferences/{$preference->id}", $updateData);

        $response->assertStatus(302);
        $preference->refresh();
        $this->assertEquals("New Name", $preference->name);
    }

    public function testUserCannotUpdateOthersPreference(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $preference = Preference::factory()->create([
            "user_id" => $otherUser->id,
            "name" => "Old Name",
        ]);

        $updateData = ["name" => "New Name"];

        $response = $this->actingAs($user)->put("/preferences/{$preference->id}", $updateData);

        $response->assertStatus(403);
        $preference->refresh();
        $this->assertEquals("Old Name", $preference->name);
    }

    public function testUserCanDeleteOwnPreference(): void
    {
        $user = User::factory()->create();
        $preference = Preference::factory()->create([
            "user_id" => $user->id,
        ]);

        $response = $this->actingAs($user)->delete("/preferences/{$preference->id}");

        $response->assertStatus(302);
        $this->assertDatabaseMissing("preferences", ["id" => $preference->id]);
    }

    public function testUserCannotDeleteOthersPreference(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $preference = Preference::factory()->create([
            "user_id" => $otherUser->id,
        ]);

        $response = $this->actingAs($user)->delete("/preferences/{$preference->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas("preferences", ["id" => $preference->id]);
    }

    public function testCannotCreatePreferenceWithInvalidData(): void
    {
        $user = User::factory()->create();

        $invalidData = [
            "user_id" => $user->id,
            "name" => null,
        ];

        $response = $this->actingAs($user)->post("/preferences", $invalidData);

        $response->assertSessionHasErrors(["name"]);
        $this->assertDatabaseMissing("preferences", [
            "user_id" => $user->id,
        ]);
    }

    public function testGuestCannotCreatePreference(): void
    {
        $preferenceData = Preference::factory()->make()->toArray();

        $response = $this->post("/preferences", $preferenceData);

        $response->assertRedirect("/login");
        $this->assertDatabaseMissing("preferences", [
            "name" => $preferenceData["name"],
        ]);
    }
}
