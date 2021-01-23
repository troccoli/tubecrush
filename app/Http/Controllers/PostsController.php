<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PostsController extends Controller
{
    public function list(): View
    {
        return view('dashboard.posts.list');
    }
}
