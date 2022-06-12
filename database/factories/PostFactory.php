<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Line;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PostFactory extends Factory
{
    public function definition(): array
    {
        $date = $this->faker->dateTime();

        return [
            'title' => $this->faker->unique()->sentence(4),
            'content' => $this->faker->realText(),
            'author_id' => User::query()->inRandomOrder()->first(),
            'photo' => 'photos/placeholder-'.mt_rand(1, 10).'.jpg',
            'photo_credit' => $this->faker->name(),
            'line_id' => mt_rand(1, Line::query()->count()),
            'likes' => mt_rand(1, 200),
            'status' => PostStatus::Published,
            'created_at' => $date,
            'updated_at' => $date,
            'published_at' => $date,
        ];
    }

    public function bySuperAdmin(): self
    {
        return $this->state(fn (array $attributes) => ['author_id' => 1]);
    }

    public function notLiked(): self
    {
        return $this->state(fn (array $attributes) => ['likes' => 0]);
    }

    public function now(): self
    {
        $now = Carbon::now();

        return $this->state(fn (array $attributes) => ['created_at' => $now, 'updated_at' => $now]);
    }

    public function draft(): self
    {
        return $this->state(
            fn ($attributes) => ['status' => PostStatus::Draft, 'published_at' => null],
        );
    }

    public function publishedNow(): self
    {
        return $this->state(fn ($attributes) => ['published_at' => Carbon::now()]);
    }

    public function withTitle(string $title): self
    {
        return $this->state(fn (array $attributes) => ['title' => $title]);
    }

    public function withoutPhotoCredit(): self
    {
        return $this->state(fn (array $attributes) => ['photo_credit' => null]);
    }

    public function withTags(): self
    {
        return $this->afterCreating(
            fn (Post $post) => $post->tags()->sync(Tag::query()->inRandomOrder()->limit(mt_rand(1, 5))->get())
        );
    }
}
