<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Tests\Browser\Browser;

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

            Browser::macro('scrollToElement', function ($selector): Browser {
                /** @var Browser $this */
                $selector = addslashes($this->resolver->format($selector));
                $this->driver->executeScript(
                    "document.querySelector(\"$selector\").scrollIntoView({block: \"center\", inline: \"center\"});"
                );

                return $this;
            });

            Browser::macro('scrollAndClick', function ($selector): Browser {
                /** @var Browser $this */
                return $this->scrollToElement($selector)->click($selector);
            });
        }
    }
}
