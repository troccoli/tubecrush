<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Line;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PostFactory extends Factory
{
    public function bySuperAdmin()
    {
        return $this->state(fn (array $attributes) => ['author_id' => 1]);
    }

    public function definition()
    {
        $date = $this->faker->dateTime();

        return [
            'title' => $this->faker->unique()->sentence(4),
            'content' => $this->faker->realText(),
            'author_id' => User::query()->inRandomOrder()->first(),
            'photo' => 'photos/placeholder-' . mt_rand(1, 10) . '.jpg',
            'photo_credit' => $this->faker->name(),
            'line_id' => mt_rand(1, Line::query()->count()),
            'likes' => mt_rand(1, 200),
            'status' => PostStatus::Published,
            'created_at' => $date,
            'updated_at' => $date,
            'published_at' => $date,
        ];
    }

    public function notLiked()
    {
        return $this->state(fn (array $attributes) => ['likes' => 0]);
    }

    public function now()
    {
        $now = Carbon::now();

        return $this->state(fn (array $attributes) => ['created_at' => $now, 'updated_at' => $now]);
    }

    public function draft()
    {
        return $this->state(
            fn ($attributes) => ['status' => PostStatus::Draft, 'published_at' => null]
        );
    }

    public function publishedNow()
    {
        return $this->state(fn ($attributes) => ['published_at' => Carbon::now()]);
    }

    public function withTitle(string $title)
    {
        return $this->state(fn (array $attributes) => ['title' => $title]);
    }

    public function withoutPhotoCredit()
    {
        return $this->state(fn (array $attributes) => ['photo_credit' => null]);
    }
}
