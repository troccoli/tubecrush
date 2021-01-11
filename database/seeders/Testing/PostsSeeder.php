<?php

namespace Database\Seeders\Testing;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    public function run(): void
    {
        $editor = User::query()->firstWhere('name', 'Editor');

        Post::factory()
            ->for($editor, 'author')
            ->count(25)
            ->create();
    }
}
