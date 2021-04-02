<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    public function run(): void
    {
        $editor = User::query()->firstWhere('email', 'editor@example.com');

        Post::factory()
            ->for($editor, 'author')
            ->count(25)
            ->afterCreating(function (Post $post): void {
                $post->tags()->sync(Tag::query()->inRandomOrder()->limit(mt_rand(1, 5))->get());
            })
            ->create();
    }
}
