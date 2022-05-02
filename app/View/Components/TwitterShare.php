<?php

namespace App\View\Components;

use App\Models\Post;
use Illuminate\View\Component;

class TwitterShare extends Component
{
    public function __construct(public Post $post)
    {
    }

    public function render()
    {
        return view('components.twitter-share');
    }

    public function text(): string
    {
        return urlencode($this->post->getTitle() . ' ' . route('single-post', ['post' => $this->post]));
    }
}
