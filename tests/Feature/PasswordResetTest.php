<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\Features;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function testResetPasswordLinkScreenCanBeRendered(): void
    {
        if (!Features::enabled(Features::resetPasswords())) {
            $this->markTestSkipped("Password updates are not enabled.");
        }

        $response = $this->get("/forgot-password");

        $response->assertStatus(200);
    }

    public function testResetPasswordLinkCanBeRequested(): void
    {
        if (!Features::enabled(Features::resetPasswords())) {
            $this->markTestSkipped("Password updates are not enabled.");
        }

        Notification::fake();

        $user = User::factory()->create();

        $this->post("/forgot-password", [
            "email" => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    public function testResetPasswordScreenCanBeRendered(): void
    {
        if (!Features::enabled(Features::resetPasswords())) {
            $this->markTestSkipped("Password updates are not enabled.");
        }

        Notification::fake();

        $user = User::factory()->create();

        $this->post("/forgot-password", [
            "email" => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPasswordNotification::class, function (object $notification) {
            $response = $this->get("/reset-password/" . $notification->token);

            $response->assertStatus(200);

            return true;
        });
    }

    public function testPasswordCanBeResetWithValidToken(): void
    {
        if (!Features::enabled(Features::resetPasswords())) {
            $this->markTestSkipped("Password updates are not enabled.");
        }

        Notification::fake();

        $user = User::factory()->create();

        $this->post("/forgot-password", [
            "email" => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPasswordNotification::class, function (object $notification) use ($user) {
            $response = $this->post("/reset-password", [
                "token" => $notification->token,
                "email" => $user->email,
                "password" => "password",
                "password_confirmation" => "password",
            ]);

            $response->assertSessionHasNoErrors();

            return true;
        });
    }
}
