<?php

namespace Database\Seeders\Testing;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'super-admin@example.com',
        ])->assignRole('Super Admin');
        User::factory()->create([
            'name' => 'Editor',
            'email' => 'editor@example.com',
        ])->assignRole('Editor');
    }
}
