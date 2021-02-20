<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    public function testRedirectsToHomepageAfterLoggingIn(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visitRoute('login')
                ->type('email', 'super-admin@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertRouteIs('home')
                ->assertAuthenticatedAs($this->superAdmin);
        });
    }
}
