<?php

namespace App\Mail;

use App\Models\Email;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Throwable;

class EmailPostedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;
    public $email;

    /**
     * Create a new message instance.
     *
     * @param array $emailData
     * @param Email $email
     */
    public function __construct( array $emailData, Email $email )
    {
        $this->emailData    = $emailData;
        $this->email        = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->from($this->email->sender_email, $this->email->sender_name)
            ->to($this->emailData['to'])
            ->subject($this->emailData['subject'])
            ->view('emails.default')
            ->text('emails.default_plain')
            ->with([
                'html' => $this->emailData['html'],
                'text' => $this->emailData['text']
            ]);

        if ( $this->emailData['cc'] ) {
            $message->cc($this->emailData['cc']);
        }

        if ( $this->emailData['bcc'] ) {
            $message->bcc($this->emailData['bcc']);
        }

        if ( count( $this->emailData['attachments'] ) > 0 ) {
            foreach ($this->emailData['attachments'] as $mailAttachment) {
                $message->attachData(
                    base64_decode( $mailAttachment['content'] ),
                    $mailAttachment['filename']
                );
            }
        }

        return $message;
    }

    /**
     * Handle a job failure.
     *
     * @param Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        $this->email->update([
            'status'    => Email::STATUS_FAILED
        ]);
    }
}
