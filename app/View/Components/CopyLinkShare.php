<?php

namespace App\View\Components;

use App\Models\Post;
use Illuminate\View\Component;

class CopyLinkShare extends Component
{
    public function __construct(public Post $post)
    {
    }

    public function render()
    {
        return view('components.copy-link-share');
    }

    public function postUrl(): string
    {
        return route('single-post', ['post' => $this->post]);
    }

    public function uniqueId(): int
    {
        return $this->post->getKey();
    }
}
