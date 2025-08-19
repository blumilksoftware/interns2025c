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

    public function testUserWithAdminRoleCanDeleteUser(): void
    {
        $admin = User::factory()->create([
            "role" => Role::ADMIN->value,
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete("/users/{$user->id}");

        $response->assertStatus(302);
        $this->assertDatabaseMissing("users", ["id" => $user->id]);
    }

    public function testUserWithoutAdminRoleCannotDeleteUser(): void
    {
        $roles = [Role::USER->value, Role::SHELTER->value];

        foreach ($roles as $role) {
            $user = User::factory()->create(["role" => $role]);
            $target = User::factory()->create();

            $response = $this->actingAs($user)->delete("/users/{$target->id}");

            $response->assertStatus(403);
            $this->assertDatabaseHas("users", ["id" => $target->id]);
        }
    }

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
