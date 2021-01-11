<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        $date = $this->faker->dateTime();
        return [
            'title' => $this->faker->sentence(4),
            'content' => $this->faker->realText(),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
