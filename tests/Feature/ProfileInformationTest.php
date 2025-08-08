<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function testProfileInformationCanBeUpdated(): void
    {
        $this->actingAs($user = User::factory()->create());

        $this->put("/user/profile-information", [
            "name" => "Test Name",
            "email" => "test@example.com",
        ]);

        $this->assertEquals("Test Name", $user->fresh()->name);
        $this->assertEquals("test@example.com", $user->fresh()->email);
    }
}
