<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testPasswordCanBeUpdated(): void
    {
        $this->actingAs($user = User::factory()->create());

        $this->put("/user/password", [
            "current_password" => "password",
            "password" => "new-password",
            "password_confirmation" => "new-password",
        ]);

        $this->assertTrue(Hash::check("new-password", $user->fresh()->password));
    }

    public function testCurrentPasswordMustBeCorrect(): void
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->put("/user/password", [
            "current_password" => "wrong-password",
            "password" => "new-password",
            "password_confirmation" => "new-password",
        ]);

        $response->assertSessionHasErrors(["current_password"], errorBag: "updatePassword");

        $this->assertTrue(Hash::check("password", $user->fresh()->password));
    }

    public function testNewPasswordsMustMatch(): void
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->put("/user/password", [
            "current_password" => "password",
            "password" => "new-password",
            "password_confirmation" => "wrong-password",
        ]);

        $response->assertSessionHasErrors(["password"], errorBag: "updatePassword");

        $this->assertTrue(Hash::check("password", $user->fresh()->password));
    }
}
