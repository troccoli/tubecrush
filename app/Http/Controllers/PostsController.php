<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('dashboard.posts');
    }
}
