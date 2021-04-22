<?php


namespace Tests\API\Token;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokenTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testGuestCannotCreateToken()
    {
        $this->json('POST', '/api/v1/tokens', ['token_name' => 'test_token'])
                ->assertStatus(401);
    }

    public function testGuestCannotLoadTokens()
    {
        $this->json('GET', '/api/v1/tokens' )
                ->assertStatus(401);
    }

    public function testUserCanCreateToken()
    {
        $tokenName = 'test_token';

        $this->actingAs($this->user)
            ->json('POST','/api/v1/tokens', [ 'token_name' => $tokenName ]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id'  => $this->user->id,
            'name'          => $tokenName,
        ]);
    }

    public function testUserCannotCreateTokenWithNoName()
    {
        $this->actingAs($this->user)
            ->json('POST','/api/v1/tokens' )
            ->assertStatus(422);
    }

    public function testUserCanLoadTokens()
    {
        $tokenName = 'test_token';
        $this->user->createToken( $tokenName );

        $response = $this->actingAs($this->user)
                        ->json('GET', '/api/v1/tokens');

        $this->assertCount( 1, $response['tokens'] );
        $this->assertEquals($tokenName, $response['tokens'][0]['name']);
        $this->assertEquals($this->user->id, $response['tokens'][0]['tokenable_id']);
    }
}
