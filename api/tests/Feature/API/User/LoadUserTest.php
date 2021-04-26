<?php


namespace Tests\Feature\API\Authentication;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class LoadUserTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanLoadProfileData()
    {
        $userData = [
            'name'  => 'John Doe',
            'email' => 'johndoe@mail.com'
        ];

        $user = User::factory()->create($userData);

        $this->actingAs($user)
            ->getJson(route('api.user'))
            ->assertStatus(200)
            ->assertJson($userData);
    }

    public function testGuestCannotLoadProfileData()
    {
        $this->getJson(route('api.user'))
            ->assertStatus(401);
    }
}
