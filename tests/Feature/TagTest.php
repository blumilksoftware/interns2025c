<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreTagCreatesTag(): void
    {
        $response = $this->post("/tags", ["name" => "friendly"]);

        $response->assertStatus(201);
        $this->assertDatabaseHas("tags", ["name" => "friendly"]);
    }

    public function testUpdateTagUpdatesTag(): void
    {
        $tag = Tag::create(["name" => "oldname"]);

        $response = $this->put("/tags/{$tag->id}", ["name" => "newname"]);

        $response->assertStatus(200);
        $this->assertDatabaseHas("tags", ["name" => "newname"]);
    }

    public function testDeleteTagDeletesTag(): void
    {
        $tag = Tag::create(["name" => "tobedeleted"]);

        $response = $this->delete("/tags/{$tag->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing("tags", ["id" => $tag->id]);
    }
}
