<?php


namespace Tests\API\Email;


use App\Http\Resources\EmailCollection;
use App\Models\Email;
use App\Models\Recipient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoadEmailsTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testGuestCannotLoadEmails()
    {
        $this->getJson( '/api/v1/emails')->assertStatus(401);
    }

    public function testNoEmailsLoaded()
    {
        $response = $this->actingAs($this->user)
            ->getJson( '/api/v1/emails');
        $json = $response->json();

        $this->assertCount(0, $json['data']);
    }

    public function testUserCanListEmailsCollection()
    {
        $recipients = Recipient::factory(2)->create()->map( function ($recipient) {
            return $recipient['id'];
        });
        $emails = Email::factory(10)->create([
            'added_by' => $this->user->id,
            'status'    => Email::STATUS_POSTED
        ]);
        foreach ($emails as $email) {
            $email->recipients()->sync($recipients);
        }

        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/emails')
            ->assertStatus(200);
        $json = $response->json();

        $emails = Email::with('recipients')->paginate(10);
        $resource = new EmailCollection($emails);
        $resourceResponse = $resource->response()->getData(true);

        $this->assertSame( $json, $resourceResponse );
    }

    public function testUserCannotLoadOthersEmails()
    {
        $recipients = Recipient::factory(2)->create()->map( function ($recipient) {
            return $recipient['id'];
        });
        $emails = Email::factory(10)->create([
            'added_by' => $this->user->id,
            'status'    => Email::STATUS_POSTED
        ]);
        foreach ($emails as $email) {
            $email->recipients()->sync($recipients);
        }

        $newUser = User::factory()->create();
        $response = $this->actingAs($newUser)
            ->getJson('/api/v1/emails')
            ->assertStatus(200);
        $json = $response->json();

        $emails = Email::with('recipients')->paginate(10);
        $resource = new EmailCollection($emails);
        $resourceResponse = $resource->response()->getData(true);

        $this->assertNotSame( $json, $resourceResponse );
        $this->assertCount(0, $json['data']);
    }
}
