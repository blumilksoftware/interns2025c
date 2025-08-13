<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Features;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    public function testUserAccountsCanBeDeleted(): void
    {
        if (!Features::hasAccountDeletionFeatures()) {
            $this->markTestSkipped("Account deletion is not enabled.");
        }

        $this->actingAs($user = User::factory()->create());

        $this->delete("/user", [
            "password" => "password",
        ]);

        $this->assertNull($user->fresh());
    }

    public function testCorrectPasswordMustBeProvidedBeforeAccountCanBeDeleted(): void
    {
        if (!Features::hasAccountDeletionFeatures()) {
            $this->markTestSkipped("Account deletion is not enabled.");
        }

        $this->actingAs($user = User::factory()->create());

        $this->delete("/user", [
            "password" => "wrong-password",
        ]);

        $this->assertNotNull($user->fresh());
    }
}
