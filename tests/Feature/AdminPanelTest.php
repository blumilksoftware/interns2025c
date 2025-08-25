<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanAccessAdminPanel(): void
    {
        $admin = User::factory()->create(["role" => "admin"]);

        $response = $this->actingAs($admin)->get("/admin");

        $response->assertStatus(200);
    }

    public function testGuestCannotAccessAdminPanel(): void
    {
        $response = $this->get("/admin");

        $response->assertRedirect(route("login"));
    }

    public function testCommonUserCannotAccessAdminPanel(): void
    {
        $user = User::factory()->create(["role" => "user"]);

        $response = $this->actingAs($user)->get("/admin");

        $response->assertStatus(403);
    }
}
