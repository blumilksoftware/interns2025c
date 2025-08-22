<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function testUserWithAdminRoleCanCreateTag(): void
    {
        $admin = User::factory()->create([
            "role" => Role::Admin->value,
        ]);

        $response = $this->actingAs($admin)->post("/tags", ["name" => "newtag"]);

        $response->assertStatus(302);
        $this->assertDatabaseHas("tags", ["name" => "newtag"]);
    }

    public function testUserWithoutAdminRoleCannotCreateTag(): void
    {
        $roles = [Role::User->value, Role::Shelter->value];

        foreach ($roles as $role) {
            $user = User::factory()->create([
                "role" => $role,
            ]);

            $response = $this->actingAs($user)->post("/tags", ["name" => "newtag"]);

            $response->assertStatus(403);
            $this->assertDatabaseMissing("tags", ["name" => "newtag"]);
        }
    }

    public function testCannotCreateTagWithoutProvidedTagName(): void
    {
        $tag = Tag::factory()->make(["name" => ""]);

        $response = $this->post("/tags", ["name" => ""]);

        $response->assertSessionHasErrors(["name"]);
        $this->assertDatabaseCount("tags", 0);
    }

    public function testCannotCreateTagWhenNameIsTooLong(): void
    {
        $nameWithExceededLength = Str::random(5000);
        $tag = Tag::factory()->make(["name" => $nameWithExceededLength]);

        $response = $this->post("/tags", ["name" => $tag->name]);

        $response->assertSessionHasErrors(["name"]);
        $this->assertDatabaseCount("tags", 0);
    }

    public function testCannotCreateTagWhenNameIsNotUnique(): void
    {
        Tag::create(["name" => "duplicate"]);

        $response = $this->post("/tags", ["name" => "duplicate"]);

        $response->assertSessionHasErrors(["name"]);
        $this->assertDatabaseCount("tags", 1);
    }

    public function testUserWithAdminRoleCanUpdateTag(): void
    {
        $tag = Tag::create(["name" => "oldname"]);

        $admin = User::factory()->create([
            "role" => Role::Admin->value,
        ]);

        $response = $this->actingAs($admin)->put("/tags/{$tag->id}", ["name" => "newname"]);

        $response->assertStatus(302);
        $this->assertDatabaseHas("tags", ["name" => "newname"]);
    }

    public function testUserWithoutAdminRoleCannotUpdateTag(): void
    {
        $roles = [Role::User->value, Role::Shelter->value];

        foreach ($roles as $index => $role) {
            $user = User::factory()->create([
                "role" => $role,
            ]);
            $tag = Tag::factory()->create([
                "name" => "oldname{$index}",
            ]);

            $response = $this->actingAs($user)->put("/tags/{$tag->id}", ["name" => "newname"]);

            $response->assertStatus(403);
            $this->assertDatabaseHas("tags", ["id" => $tag->id, "name" => "oldname{$index}"]);
        }
    }

    public function testUpdateTagFailsWithEmptyName(): void
    {
        $tag = Tag::factory()->create(["name" => "validname"]);

        $response = $this->put("/tags/{$tag->id}", ["name" => ""]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(["name"]);
        $this->assertDatabaseHas("tags", ["name" => "validname"]);
    }

    public function testCannotUpdateTagWhenNameIsTooLong(): void
    {
        $nameWithExceededLength = Str::random(5000);
        $tag = Tag::factory()->create(["name" => "short"]);

        $response = $this->put("/tags/{$tag->id}", ["name" => $nameWithExceededLength]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(["name"]);
        $this->assertDatabaseHas("tags", ["name" => "short"]);
    }

    public function testCannotUpdateTagWhenNameIsNotUnique(): void
    {
        Tag::factory()->create(["name" => "existing"]);
        $tag = Tag::factory()->create(["name" => "tochange"]);

        $response = $this->put("/tags/{$tag->id}", ["name" => "existing"]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(["name"]);
        $this->assertDatabaseHas("tags", ["name" => "tochange"]);
    }

    public function testUpdatingNonExistentTagReturnsNotFound(): void
    {
        $response = $this->put("/tags/999999", ["name" => "doesnotexist"]);

        $response->assertStatus(404);
    }

    public function testUserWithAdminRoleCanDeleteTag(): void
    {
        $user = User::factory()->create([
            "role" => Role::Admin->value,
        ]);
        $tag = Tag::create(["name" => "tobedeleted"]);

        $response = $this->actingAs($user)->delete("/tags/{$tag->id}");

        $response->assertStatus(302);
        $this->assertDatabaseMissing("tags", ["id" => $tag->id]);
    }

    public function testUserWithoutAdminRoleCannotDeleteTag(): void
    {
        $roles = [Role::User->value, Role::Shelter->value];

        foreach ($roles as $role => $index) {
            $user = User::factory()->create([
                "role" => $role,
            ]);
            $tag = Tag::factory()->create(["name" => "tobedeleted$index"]);

            $response = $this->actingAs($user)->delete("/tags/{$tag->id}");

            $response->assertStatus(403);
            $this->assertDatabaseHas("tags", ["id" => $tag->id]);
        }
    }

    public function testDeletingNonExistentTagReturnsNotFound(): void
    {
        $response = $this->delete("/tags/999999");

        $response->assertStatus(404);
    }
}
