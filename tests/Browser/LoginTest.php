<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    public function testRedirectsToHomepageAfterLoggingIn(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'super-admin@example.com')
                ->type('password', 'password')
                ->press('LOGIN')
                ->assertRouteIs('home');
        });
    }
}