<?php

namespace App\Http\Livewire\Posts;

use Livewire\Component;

class SinglePost extends Component
{
    public $post;

    public function render()
    {
        return view('livewire.posts.single-post');
    }
}
