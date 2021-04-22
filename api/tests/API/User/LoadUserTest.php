<?php


namespace Tests\API\Authentication;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class LoadUserTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotLoadProfile()
    {
        $this->getJson('/api/v1/user')
            ->assertStatus(401);
    }

    public function testUserCanLoadTheirProfile()
    {
        $email = 'johndoe@mail.com';
        $password = 'secret';

        $user = User::factory()->create([
            'name'              => 'John Doe',
            'email'             => $email,
            'password'          => bcrypt($password),
        ]);

        $response = $this->actingAs($user)->getJson('/api/v1/user');

        $this->assertEquals( $user->toArray(), $response->json() );
    }
}
