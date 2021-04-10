<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PostsByTagsController extends Controller
{
    public function __invoke(Request $request, string $slug): View
    {
        /** @var Tag $tag */
        $tag = Tag::whereSlug($slug)->first();
        return view('tags', ['id' => $tag->getId(), 'name' => $tag->getName()]);
    }
}
