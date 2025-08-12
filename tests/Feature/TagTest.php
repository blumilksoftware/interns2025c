<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanCreateTag(): void
    {
        $response = $this->post("/tags", ["name" => "friendly"]);

        $response->assertStatus(201);
        $this->assertDatabaseHas("tags", ["name" => "friendly"]);
    }

    public function testCreateTagFailsWithMissingName(): void
    {
        $response = $this->post("/tags", ["name" => ""]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(["name"]);
        $this->assertDatabaseCount("tags", 0);
    }

    public function testCreateTagFailsWhenNameIsTooLong(): void
    {
        $longName = str_repeat("a", 301);

        $response = $this->post("/tags", ["name" => $longName]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(["name"]);
        $this->assertDatabaseCount("tags", 0);
    }

    public function testCreateTagFailsWhenNameIsNotUnique(): void
    {
        Tag::create(["name" => "duplicate"]);

        $response = $this->post("/tags", ["name" => "duplicate"]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(["name"]);
        $this->assertDatabaseCount("tags", 1);
    }

    public function testUserCanUpdateTag(): void
    {
        $tag = Tag::create(["name" => "oldname"]);

        $response = $this->put("/tags/{$tag->id}", ["name" => "newname"]);

        $response->assertStatus(200);
        $this->assertDatabaseHas("tags", ["name" => "newname"]);
    }

    public function testUpdateTagFailsWithEmptyName(): void
    {
        $tag = Tag::create(["name" => "validname"]);

        $response = $this->put("/tags/{$tag->id}", ["name" => ""]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(["name"]);
        $this->assertDatabaseHas("tags", ["name" => "validname"]);
    }

    public function testUpdateTagFailsWhenNameIsTooLong(): void
    {
        $tag = Tag::create(["name" => "short"]);

        $longName = str_repeat("a", 301);

        $response = $this->put("/tags/{$tag->id}", ["name" => $longName]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(["name"]);
        $this->assertDatabaseHas("tags", ["name" => "short"]);
    }

    public function testUpdateTagFailsWhenNameIsNotUnique(): void
    {
        Tag::create(["name" => "existing"]);
        $tag = Tag::create(["name" => "tochange"]);

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

    public function testUserCanDeleteTag(): void
    {
        $tag = Tag::create(["name" => "tobedeleted"]);

        $response = $this->delete("/tags/{$tag->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing("tags", ["id" => $tag->id]);
    }

    public function testDeletingNonExistentTagReturnsNotFound(): void
    {
        $response = $this->delete("/tags/999999");

        $response->assertStatus(404);
    }
}
