<?php


namespace Tests\API\Email;


use App\Mail\EmailPostedMail;
use App\Models\Email;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateEmailTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $validMailBody;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $text = Storage::get('email_text.html');
        $html = Storage::get('email.html');

        $this->validMailBody =  [
            'from'  => [
                'email'   => 'johndoe@mail.com',
                'name'    => 'John Doe'
            ],
            'to'  => [
                [
                    'email'   => 'johndoe2@mail.com',
                    'name'    => 'John Doe'
                ],
                [
                    'email'   => 'johndoe3@mail.com',
                    'name'    => 'John Doe'
                ],
            ],
            'cc'  => [
                [
                    'email'   => 'johndoe2@mail.com',
                    'name'    => 'John Doe'
                ],
                [
                    'email'   => 'johndoe3@mail.com',
                    'name'    => 'John Doe'
                ],
            ],
            'reply_to' => [
                'name'    => 'John Doe',
                'email'   => 'johndoe3@mail.com',
            ],
            'subject' => 'You have projects to review!',
            'text'    => $text,
            'html'    => $html
        ];
    }

    public function testGuestCannotCreateEmail()
    {
        $mailBody = [
          'from'    => [
              'email'   => 'johndoe@mail.com',
              'name'    => 'John Doe'
          ]
        ];

        $this->postJson('/api/v1/emails', $mailBody)
            ->assertStatus(401);
    }

    public function testUserCannotCreateInvalidEmail ()
    {
        $mailBody = [
            'from'    => [
                'email'   => 'johndoe@mail.com',
                'name'    => 'John Doe'
            ]
        ];

        $this->actingAs($this->user)
            ->postJson('/api/v1/emails', $mailBody)
            ->assertStatus(422);

        $this->assertDatabaseMissing('emails', [ 'sender_email' => $mailBody['from']['email']]);
    }

    public function testUserCanCreateValidEmail()
    {
        $this->actingAs($this->user)
            ->postJson('/api/v1/emails', $this->validMailBody)
            ->assertStatus(201);

        $this->assertDatabaseHas('emails', [
            'sender_email'  => $this->validMailBody['from']['email'],
            'subject'       => $this->validMailBody['subject']
        ]);

        $this->assertDatabaseHas('recipients', [
            'email'  => $this->validMailBody['to'][0]['email']
        ]);

        $this->assertDatabaseHas('recipients', [
            'email'  => $this->validMailBody['to'][1]['email']
        ]);
    }

    public function testMailWasQueuedOnValidRequest() {
        Mail::fake();

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/emails', $this->validMailBody)
            ->assertStatus(201);

        $json = $response->json();

        Mail::assertQueued(EmailPostedMail::class, 1);
        Mail::assertQueued(EmailPostedMail::class, function ($mail) use ($json)  {
            return $mail->email->id === $json['data']['id'];
        });
    }
}
