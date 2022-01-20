<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'email' => 'super-admin@example.com',
        ])->assignRole(UserRoles::SuperAdmin->value);
        User::factory()->create([
            'email' => 'editor@example.com',
        ])->assignRole(UserRoles::Editor->value);
    }
}
