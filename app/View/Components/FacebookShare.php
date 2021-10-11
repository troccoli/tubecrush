<?php

namespace App\View\Components;

use App\Models\Post;
use Illuminate\View\Component;

class FacebookShare extends Component
{
    public function __construct(public Post $post)
    {
    }

    public function postUrl(): string
    {
        return urlencode(route('single-post', ['post' => $this->post]));
    }

    public function render()
    {
        return view('components.facebook-share');
    }
}
