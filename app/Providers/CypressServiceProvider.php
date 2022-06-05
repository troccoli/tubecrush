<?php

namespace App\Providers;

use App\Http\Middleware\EncryptCookies;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CypressServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        if ($this->app->environment('cypress')) {
            Route::middleware(EncryptCookies::class)
                ->prefix('/__cypress__')
                ->group(function () {
                    Route::get('/accept-cookies', function (): Response {
                        return response('&nbsp;')
                            ->cookie(
                                config('cookies.consent.cookie_name'),
                                config('cookies.consent.consent_value'),
                                config('cookies.consent.consent_cookie_lifetime')
                            );
                    });
                    Route::get('/refuse-cookies', function (): Response {
                        return response('&nbsp;')
                            ->cookie(
                                config('cookies.consent.cookie_name'),
                                config('cookies.consent.refuse_value'),
                                config('cookies.consent.refuse_cookie_lifetime')
                            );
                    });
                });
        }
    }
}
