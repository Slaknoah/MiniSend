<?php


namespace Tests\API\Authentication;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function testUserCanRegister()
    {
        $user = [
            'name'              => 'John Doe',
            'email'             => 'johndoe@mail.com',
            'password'          => 'secret',
            'confirm_password'  => 'secret'
        ];
        $response = $this->post('/register', $user );

        $response->assertStatus(204);
    }
}
