<?php


namespace Tests\Feature\Authentication;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function testRegistersSuccessfully()
    {
        $payload = [
            'name'              => 'John Doe',
            'email'             => 'johndoe@mail.com',
            'password'          => 'secret',
            'confirm_password'  => 'secret'
        ];

        $this->postJson('/register', $payload )
            ->assertStatus(204);
    }

    public function testRequiresNameEmailAndPassword()
    {
        $expectedErrors = [
            'errors'    => [
                'name' => ['The name field is required.'],
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ]
        ];

        $this->postJson('/register' )
            ->assertStatus(422)
            ->assertJson($expectedErrors);
    }

    public function testMatchesPasswordConfirmation()
    {
        $payload = [
            'name'              => 'John Doe',
            'email'             => 'johndoe@mail.com',
            'password'          => 'secret',
            'confirm_password'  => 'secret_new'
        ];

        $expectedErrors = [
            'errors'    => [
                'confirm_password' => ['The confirm password and password must match.'],
            ]
        ];

        $this->postJson('/register', $payload )
            ->assertStatus(422)
            ->assertJson($expectedErrors);
    }
}
