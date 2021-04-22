<?php


namespace App\Services\Email;


use App\Jobs\SendEmailJob;
use App\Mail\EmailPostedMail;
use App\Models\Email;
use App\Models\Recipient;
use App\Services\Gravatar;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreateEmail
{
    private $data;

    /**
     * CreateEmail constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function save() {
        $email                  = new Email();
        $email->sender_name     = $this->data['from']['name'] ?? null;
        $email->sender_email    = $this->data['from']['email'];
        $email->subject         = $this->data['subject'];

        if ( isset($this->data['cc']) ) {
            $cc = [];
            foreach ($this->data['cc'] as $ccItem) {
                $cc[] = [
                    'name'  => $ccItem['name'] ?? null,
                    'email' => $ccItem['email']
                ];
            }
            $email->cc = $cc;
        }

        if ( isset($this->data['bcc']) ) {
            $bcc = [];
            foreach ($this->data['bcc'] as $bccItem) {
                $bcc[] = [
                    'name'  => $bccItem['name'] ?? null,
                    'email' => $bccItem['email']
                ];
            }
            $email->bcc = $bcc;
        }

        if ( isset($this->data['reply_to']) ) {
            $email->reply_to  = [
                'name'  => $this->data['reply_to']['name'] ?? null,
                'email' => $this->data['reply_to']['email']
            ];
        }

        $email->added_by = auth()->user()->id;

        $email->save();

        $recipientIDs = [];
        foreach ($this->data['to'] as $recipient) {
            $recipientModel = Recipient::firstOrCreate(
                ['email' => $recipient['email']],
                ['name' => $recipient['name']]
            );

            $gravatar = new Gravatar( $recipientModel->email );
            $gravatar->update( $recipientModel );

            $recipientIDs[] = $recipientModel->id;
        }
        $email->recipients()->sync($recipientIDs);

        Mail::queue( new EmailPostedMail( $this->data, $email ) );

        return $email;
    }
}
