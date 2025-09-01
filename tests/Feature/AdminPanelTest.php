<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanAccessAdminPanel(): void
    {
        $admin = User::factory()->create(["role" => Role::Admin->value]);

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
        $user = User::factory()->create(["role" => Role::User->value]);

        $response = $this->actingAs($user)->get("/admin");

        $response->assertStatus(403);
    }

    public function testAdminCanChangePetAcceptedFlag(): void
    {
        $admin = User::factory()->create(["role" => Role::Admin->value]);
        $pet = Pet::factory()->create(["is_accepted" => false]);

        $response = $this->actingAs($admin)->post("/admin/pets/{$pet->id}/accept");

        $response->assertStatus(302);
        $pet->refresh();
        $this->assertDatabaseHas("pets", ["id" => $pet->id, "is_accepted" => true]);
    }

    public function testNonAdminUserCannotChangePetAcceptedFlag(): void
    {
        $user = User::factory()->create(["role" => Role::User->value]);
        $pet = Pet::factory()->create(["is_accepted" => false]);

        $response = $this->actingAs($user)->post("/admin/pets/{$pet->id}/accept");

        $response->assertStatus(403);
        $pet->refresh();
        $this->assertDatabaseHas("pets", ["id" => $pet->id, "is_accepted" => false]);
    }

    public function testPetIsDeletedWhenAdminRejectsIt(): void
    {
        $admin = User::factory()->create(["role" => Role::Admin->value]);
        $pet = Pet::factory()->create(["is_accepted" => false]);

        $response = $this->actingAs($admin)->delete("/pets/{$pet->id}");

        $response->assertStatus(302);
        $this->assertSoftDeleted("pets", ["id" => $pet->id]);
    }
}
