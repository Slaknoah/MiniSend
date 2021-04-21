<?php

namespace Database\Seeders;

use App\Models\Recipient;
use Illuminate\Database\Seeder;

class RecipientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Recipient::factory(20)->create();
    }
}
