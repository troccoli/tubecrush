<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DashboardTest extends DuskTestCase
{
    public function testDashboard()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visitRoute('dashboard')
                ->assertSee("Super Admin's Dashboard")
                ->assertSee('Welcome to your dashboard.')
                ->assertSeeLink('Users')
                ->logout();
            $browser->loginAs($this->editor)
                ->visitRoute('dashboard')
                ->assertSee("Editor's Dashboard")
                ->assertSee('Welcome to your dashboard.')
                ->assertDontSee('Users')
                ->logout();
        });
    }
}
