<?php


namespace Tests\API\Authentication;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

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
    }

    public function testLogsInSuccessfully()
    {
        User::factory()->create([
            'email'     => 'testuser@mail.com',
            'password'  => bcrypt('password')
        ]);

        $payload = [
            'email'     => 'testuser@mail.com',
            'password'  => 'password'
        ];

        $this->postJson('login', $payload )
            ->assertStatus(204);
    }
}
