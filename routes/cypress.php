<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/accept-cookies', function (): Response {
    return response('&nbsp;')
        ->cookie(
            config('cookies.consent.cookie_name'),
            config('cookies.consent.consent_value'),
            config('cookies.consent.consent_cookie_lifetime')
        );
})->name('cypress.accept-cookies');

Route::get('/refuse-cookies', function (): Response {
    return response('&nbsp;')
        ->cookie(
            config('cookies.consent.cookie_name'),
            config('cookies.consent.refuse_value'),
            config('cookies.consent.refuse_cookie_lifetime')
        );
})->name('cypress.refuse-cookies');
