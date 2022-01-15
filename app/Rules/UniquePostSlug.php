<?php

namespace App\Rules;

use App\Models\AlternativePostSlug;
use App\Models\Post;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Contracts\Validation\Rule;

class UniquePostSlug implements Rule
{
    public function message(): string
    {
        return 'A slug for this :attribute has already been used in the past.';
    }

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        $slug = SlugService::createSlug(Post::class, 'slug', $value);
        return null === AlternativePostSlug::findBySlug($slug) &&
            null === Post::findBySlug($slug);
    }
}
