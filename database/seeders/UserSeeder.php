<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'email' => 'super-admin@example.com',
        ])->assignRole('Super Admin');
        User::factory()->create([
            'email' => 'editor@example.com',
        ])->assignRole('Editor');
    }
}
