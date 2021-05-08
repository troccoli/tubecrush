<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class DuskServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        if (!$this->app->environment('production')) {
            Route::get('/cookietest', function () {
                return 'TEST';
            })->name('dusk.cookies-consent');
        }
    }
}
