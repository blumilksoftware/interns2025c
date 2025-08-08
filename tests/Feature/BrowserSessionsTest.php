<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrowserSessionsTest extends TestCase
{
    use RefreshDatabase;

    public function testOtherBrowserSessionsCanBeLoggedOut(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->delete("/user/other-browser-sessions", [
            "password" => "password",
        ]);

        $response->assertSessionHasNoErrors();
    }
}
