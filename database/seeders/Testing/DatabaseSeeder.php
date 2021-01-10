<?php

namespace Database\Seeders\Testing;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(\Database\Seeders\DatabaseSeeder::class);
        $this->call(UserSeeder::class);
    }
}
