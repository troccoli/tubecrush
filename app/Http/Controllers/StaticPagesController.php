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

    public function legalInformation(): View
    {
        return view('static.legal-information');
    }

    public function photoRemoval(): View
    {
        return view('static.photo-removal');
    }

    public function pressEnquiries(): View
    {
        return view('static.press-enquiries');
    }

    public function sendCrushSuccess(): View
    {
        return view('static.send-crush-success');
    }
}
