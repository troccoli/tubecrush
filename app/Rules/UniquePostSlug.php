<?php

namespace App\Rules;

use App\Models\AlternativePostSlug;
use App\Models\Post;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Contracts\Validation\Rule;

class UniquePostSlug implements Rule
{
    public function __construct(private ?Post $excludingPost = null)
    {
    }

    public function message(): string
    {
        return 'A slug for this :attribute has already been used in the past.';
    }

    /**
     * @param string $attribute
     * @param mixed $value
     */
    public function passes($attribute, $value): bool
    {
        $slug = SlugService::createSlug(Post::class, 'slug', $value);

        if (AlternativePostSlug::findBySlug($slug)) {
            return false;
        }

        $post = Post::query()
            ->when($this->excludingPost, fn($query) => $query->whereKeyNot($this->excludingPost))
            ->whereSlug($slug)->first();
        if ($post) {
            return false;
        }

        return true;
    }
}
