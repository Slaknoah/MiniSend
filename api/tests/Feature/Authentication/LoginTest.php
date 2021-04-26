<?php


namespace Tests\Feature\Authentication;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected string $auth_guard = 'web';

    public function testLoginRequiresEmailAndPassword()
    {
        $expectedResponse =  [
            'errors' => [
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ]
        ];

        $this->postJson('login')
            ->assertStatus(422)
            ->assertJson($expectedResponse);

        $this->assertGuest($this->auth_guard);
    }

    public function testLoginFailsIfUserDoesNotExist()
    {
        $payload = [
            'email'     => 'testuser@mail.com',
            'password'  => 'password'
        ];

        $expectedResponse =  [
            'error' => 'invalid_credentials'
        ];

        $this->postJson('login', $payload )
            ->assertStatus(401)
            ->assertJson($expectedResponse);

        $this->assertGuest($this->auth_guard);
    }

    public function testUserLogsInSuccessfully()
    {
        $user = User::factory()->create([
            'email'     => 'testuser@mail.com',
            'password'  => bcrypt('password')
        ]);

        $payload = [
            'email'     => 'testuser@mail.com',
            'password'  => 'password'
        ];

        $this->postJson('login', $payload )
            ->assertStatus(204);

        $this->assertEquals(Auth::check(), true);
        $this->assertEquals(Auth::user()->email, $user->email);
        $this->assertAuthenticated($this->auth_guard);
        $this->assertAuthenticatedAs($user, $this->auth_guard);
    }
}
