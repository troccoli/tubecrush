<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (!$this->app->environment('production')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }

    public function boot(): void
    {
    }
}
