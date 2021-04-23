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

        // Remove extra fields that might be included in request
        $emailUserValidFields = array_flip( [ 'name', 'email' ] );

        $sanitizedToList = [];
        foreach ($this->data['to'] as $recipient) {
            $sanitizedToList[] = array_intersect_key( $recipient, $emailUserValidFields );
        }
        $this->data['to'] = $sanitizedToList;

        if ( isset($this->data['cc']) ) {
            $sanitizedCCList = [];
            foreach ($this->data['cc'] as $ccItem) {
                $sanitizedCCList[] = array_intersect_key( $ccItem, $emailUserValidFields );
            }
            $email->cc = $this->data['cc'] = $sanitizedCCList;
        }

        if ( isset($this->data['bcc']) ) {
            $sanitizedBCCList = [];
            foreach ($this->data['bcc'] as $bccItem) {
                $sanitizedBCCList[] = array_intersect_key( $bccItem, $emailUserValidFields );
            }
            $email->bcc = $this->data['bcc'] = $sanitizedBCCList;
        }

        if ( isset($this->data['reply_to']) ) {
            $email->reply_to  = array_intersect_key( $this->data['reply_to'], $emailUserValidFields );
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
