<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if($this->command->confirm('Do you want to refresh tha database?')){
            $this->command->call('migrate:refresh');
            $this->command->info('Database was refreshed');
        }
        $this->call([
            UserSeeder::class,
            ////composer dump-autoload
        ]);
    }
}
