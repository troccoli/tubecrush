<?php

namespace App\Http\Controllers;

use App\Models\Line;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PostsByLinesController extends Controller
{
    public function __invoke(Request $request, string $slug): View
    {
        /** @var Line $line */
        $line = Line::whereSlug($slug)->first();
        return view('lines', ['id' => $line->getKey(), 'name' => $line->getName()]);
    }
}
