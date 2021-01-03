<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class StaticPagesController extends Controller
{
    public function aboutUs(): View
    {
        return view('static.about-us');
    }

    public function guidelines(): View
    {
        return view('static.guidelines');
    }
}
