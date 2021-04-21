<?php

namespace App\Listeners;

use App\Models\Email;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SetEmailStatusFailed
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $email = Email::find( $event->data['email']->id );
        $email->update([ 'status' => Email::STATUS_FAILED ]);
    }
}
