<?php

namespace App\Http\Controllers;

use App\Models\Post;

class SinglePostController extends Controller
{
    public function __invoke(Post $post)
    {
        return view('single-post', compact('post'));
    }
}
