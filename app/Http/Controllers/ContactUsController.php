<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('static.contact-us');
    }
}
