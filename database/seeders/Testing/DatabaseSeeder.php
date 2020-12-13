<?php

namespace Database\Seeders\Testing;

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
        $this->call(\Database\Seeders\DatabaseSeeder::class);
        $this->call(UserSeeder::class);
    }
}
