<?php

namespace Database\Seeders;

use App\Models\Email;
use App\Models\Recipient;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $user = User::findOrFail(1);
        $recipients = Recipient::select('id')->limit(random_int( 1, 4))->get();

        $emails = Email::factory(20)->create([
            'added_by' => $user->id,
            'status'    => Email::STATUS_POSTED
        ]);

        foreach ($emails as $email) {
            $email->recipients()->sync($recipients);
        }

        $recipients = Recipient::select('id')->limit(random_int( 1, 4))->get();

        $emails = Email::factory(20)->create([
            'added_by' => $user->id,
            'status'    => Email::STATUS_SENT,
        ]);

        foreach ($emails as $email) {
            $email->recipients()->sync($recipients);
        }

        $recipients = Recipient::select('id')->limit(random_int( 1, 4))->get();

        $emails = Email::factory(20)->create([
            'added_by' => $user->id,
            'status'    => Email::STATUS_FAILED
        ]);

        foreach ($emails as $email) {
            $email->recipients()->sync($recipients);
        }
    }
}
