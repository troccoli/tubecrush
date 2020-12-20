<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterUser extends Controller
{
    public function __invoke(Request $request)
    {
        return view('dashboard.register-user');
    }
}
