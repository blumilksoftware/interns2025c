<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function testRegistrationScreenCanBeRendered(): void
    {
        if (!Features::enabled(Features::registration())) {
            $this->markTestSkipped("Registration support is not enabled.");
        }

        $response = $this->get("/register");

        $response->assertStatus(200);
    }

    public function testRegistrationScreenCannotBeRenderedIfSupportIsDisabled(): void
    {
        if (Features::enabled(Features::registration())) {
            $this->markTestSkipped("Registration support is enabled.");
        }

        $response = $this->get("/register");

        $response->assertStatus(404);
    }

    public function testNewUsersCanRegister(): void
    {
        if (!Features::enabled(Features::registration())) {
            $this->markTestSkipped("Registration support is not enabled.");
        }

        $response = $this->post("/register", [
            "name" => "Test User",
            "email" => "test@example.com",
            "password" => "password",
            "password_confirmation" => "password",
            "role" => Role::USER->value,
            "terms" => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route("dashboard", absolute: false));
    }
}
