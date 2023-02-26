<?php

namespace Database\Factories;

use App\Models\AlternativePostSlug;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AlternativePostSlugFactory extends Factory
{
    public function definition(): array
    {
        return [
            'slug' => Str::slug($this->faker->sentence(4)),
            'post_id' => Post::factory()->create()->getKey(),
        ];
    }
}
