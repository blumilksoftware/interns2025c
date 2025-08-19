<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanViewOwnProfile(): void
    {
        $admin = User::factory()->create([
            "role" => Role::ADMIN->value,
        ]);

        $response = $this->actingAs($admin)->get("/profile");

        $response->assertStatus(200);
    }

    public function testUserCanViewOwnProfile(): void
    {
        $user = User::factory()->create([
            "role" => Role::USER->value,
        ]);

        $response = $this->actingAs($user)->get("/profile");

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    public function testUnauthenticatedCannotAccessProfile(): void
    {
        $response = $this->get("/profile");

        $response->assertRedirect("/login");
    }
}
