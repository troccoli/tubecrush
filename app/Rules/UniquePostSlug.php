<?php

namespace App\Rules;

use App\Models\AlternativePostSlug;
use App\Models\Post;
use Closure;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Contracts\Validation\ValidationRule;

class UniquePostSlug implements ValidationRule
{
    private const MESSAGE = 'A slug for this :attribute has already been used in the past.';

    public function __construct(private ?Post $excludingPost = null)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $slug = SlugService::createSlug(Post::class, 'slug', $value);

        if (AlternativePostSlug::findBySlug($slug)) {
            $fail(self::MESSAGE);
        }

        $post = Post::query()
            ->when($this->excludingPost, fn ($query) => $query->whereKeyNot($this->excludingPost))
            ->whereSlug($slug)->first();
        if ($post) {
            $fail(self::MESSAGE);
        }
    }
}
