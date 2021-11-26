<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userCount = max((int)$this->command->ask('How many users would  you like?',20),1);
        \App\Models\User::factory()->newUser()->create();
        \App\Models\User::factory($userCount)->create();
    }
}
