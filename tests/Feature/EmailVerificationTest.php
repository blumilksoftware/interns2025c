<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Laravel\Fortify\Features;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function testEmailVerificationScreenCanBeRendered(): void
    {
        if (!Features::enabled(Features::emailVerification())) {
            $this->markTestSkipped("Email verification not enabled.");
        }

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get("/email/verify");

        $response->assertStatus(200);
    }

    public function testEmailCanBeVerified(): void
    {
        if (!Features::enabled(Features::emailVerification())) {
            $this->markTestSkipped("Email verification not enabled.");
        }

        Event::fake();

        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            "verification.verify",
            now()->addMinutes(60),
            ["id" => $user->id, "hash" => sha1($user->email)],
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route("dashboard", absolute: false) . "?verified=1");
    }

    public function testEmailCanNotVerifiedWithInvalidHash(): void
    {
        if (!Features::enabled(Features::emailVerification())) {
            $this->markTestSkipped("Email verification not enabled.");
        }

        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            "verification.verify",
            now()->addMinutes(60),
            ["id" => $user->id, "hash" => sha1("wrong-email")],
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
