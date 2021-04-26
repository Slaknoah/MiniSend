<?php


namespace Tests\Feature\API\Token;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokenTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->user = User::factory()->create();
    }

    public function testGuestCannotCreateToken()
    {
        $this->postJson(route('api.tokens.create'))
            ->assertStatus(401);
    }

    public function testGuestCannotLoadTokens()
    {
        $this->getJson(route('api.tokens.get'))
            ->assertStatus(401);
    }

    public function testUserCanCreateToken()
    {
        $payload = [ 'token_name' => 'Test token' ];

        $this->actingAs($this->user)
            ->postJson(route('api.tokens.create'), $payload)
            ->assertStatus(200);

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id'  => $this->user->id,
            'name'          => $payload['token_name'],
        ]);
    }

    public function testCannotCreateTokenWithoutTokenName()
    {
        $this->actingAs($this->user)
            ->postJson(route('api.tokens.create'))
            ->assertStatus(422);
    }

    public function testUserCanLoadTokensList()
    {
        $this->user->createToken('Test Token');

        $this->actingAs($this->user)
            ->getJson(route('api.tokens.get'))
            ->assertStatus(200)
            ->assertJson([
                'tokens' => [
                    [
                        'tokenable_id'  => $this->user->id,
                        'name'          => 'Test Token'
                    ]
                ]
            ]);
    }

    public function testUserCanRevokeToken()
    {
        $token = $this->user->createToken('Test token');
        $token_id = $token->toArray()['accessToken']->id;

        $this->actingAs($this->user)
            ->deleteJson(route('api.tokens.revoke', ['tokenID' => $token_id]))
            ->assertStatus(204);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id'  => $this->user->id,
            'name'          => 'Test token',
            'id'            => $token_id
        ]);
    }
}
