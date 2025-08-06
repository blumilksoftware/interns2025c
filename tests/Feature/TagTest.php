<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Tag;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_tag_redirects()
    {
        $response = $this->post('/tags', ['name' => 'friendly']);
        $response->assertStatus(201);
        $this->assertDatabaseHas('tags', ['name' => 'friendly']);
    }

    public function test_update_tag_redirects()
    {
        $tag = Tag::create(['name' => 'oldname']);
        $response = $this->put("/tags/{$tag->id}", ['name' => 'newname']);
        $response->assertStatus(200);
        $this->assertDatabaseHas('tags', ['name' => 'newname']);
    }

    public function test_delete_tag_redirects()
    {
        $tag = Tag::create(['name' => 'tobedeleted']);
        $response = $this->delete("/tags/{$tag->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }
}
