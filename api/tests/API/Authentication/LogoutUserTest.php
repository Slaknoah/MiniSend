<?php


namespace Tests\API\Authentication;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutUserTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanLogout()
    {
        $this->be(User::factory()->create());

        $response = $this->post('/logout');

        $this->assertGuest();
    }
}
