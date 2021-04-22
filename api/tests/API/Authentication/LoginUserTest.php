<?php


namespace Tests\API\Authentication;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function testUserCanLogin()
    {
        $email = 'johndoe@mail.com';
        $password = 'secret';

        User::factory()->create([
            'name'              => 'John Doe',
            'email'             => $email,
            'password'          => bcrypt($password),
        ]);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => $password
        ] );

        $response->assertStatus(204);
    }

    public function testUserCannotLoginWithNonExistentEmail()
    {
        $email = 'johndoe@mail.com';
        $password = 'secret';

        User::factory()->create([
            'name'              => 'John Doe',
            'email'             => $email,
            'password'          => bcrypt($password),
        ]);

        $response = $this->post('/login', [
            'email' => 'johndoe2@mail.com',
            'password' => $password
        ] );

        $response->assertStatus(403);
    }

    public function testUserCannotLoginWithIncorrectPassword()
    {
        $email = 'johndoe@mail.com';
        $password = 'secret';

        User::factory()->create([
            'name'              => 'John Doe',
            'email'             => $email,
            'password'          => bcrypt($password),
        ]);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'secret2'
        ] );

        $response->assertStatus(403);
    }
}
