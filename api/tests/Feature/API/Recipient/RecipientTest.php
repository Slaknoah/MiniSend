<?php


namespace Tests\Feature\API\Recipient;


use App\Http\Resources\RecipientResource;
use App\Models\Recipient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipientTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testGuestCannotLoadRecipientData()
    {
        $this->getJson( '/api/v1/recipients/1')->assertStatus(401);
    }

    public function testUserCanGetRecipientData()
    {
        $recipient = Recipient::factory()->create();

        $response = $this->actingAs($this->user)->getJson("/api/v1/recipients/$recipient->id")
            ->assertStatus(200);
        $json = $response->json();

        $resource = new RecipientResource( Recipient::find($recipient->id) );
        $resourceResponse = $resource->response()->getData(true);

        $this->assertSame( $json, $resourceResponse );
    }
}
