<?php

namespace Database\Factories;

use App\Models\Line;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        $date = $this->faker->dateTime();
        return [
            'title' => $this->faker->unique()->sentence(4),
            'content' => $this->faker->realText(),
            'author_id' => mt_rand(1, User::query()->count()),
            'photo' => 'photos/placeholder-'.mt_rand(1, 10).'.jpg',
            'photo_credit' => $this->faker->name(),
            'line_id' => mt_rand(1, Line::query()->count()),
            'likes' => mt_rand(1, 200),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }

    public function now()
    {
        return $this->state(function (array $attributes): array {
            return [
                'created_at' => Carbon::now(),
            ];
        });
    }

    public function bySuperAdmin()
    {
        return $this->state(function (array $attributes): array {
            return [
                'author_id' => 1,
            ];
        });
    }

    public function withoutPhotoCredit()
    {
        return $this->state(function (array $attributes): array {
            return [
                'photo_credit' => null,
            ];
        });
    }

    public function withTitle(string $title)
    {
        return $this->state(function (array $attributes) use ($title): array {
            return [
                'title' => $title,
            ];
        });
    }

    public function notLiked()
    {
        return $this->state(function (array $attributes): array {
            return [
                'likes' => 0,
            ];
        });
    }
}
