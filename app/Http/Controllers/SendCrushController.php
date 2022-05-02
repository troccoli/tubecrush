<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SendCrushController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('send-crush');
    }
}
