<?php


namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        User::factory()->create([
            'email' => 'user@mail.com',
            'password'  => bcrypt('password')
        ]);
    }
}
