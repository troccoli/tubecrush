<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SinglePostController extends Controller
{
    public function __invoke(Post $post)
    {
        throw_if($post->isDraft(), new ModelNotFoundException());

        return view('single-post', compact('post'));
    }
}
