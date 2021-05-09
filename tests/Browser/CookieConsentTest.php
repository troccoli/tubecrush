<?php

namespace Tests\Browser;

use Tests\DuskTestCase;

class CookieConsentTest extends DuskTestCase
{
    public function testCookieConsent(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->deleteCookie(config('cookies.consent.cookie_name'))
                ->visitRoute('home')
                ->assertCookieMissing(config('cookies.consent.cookie_name'))
                ->assertVisible('@cookie-consent-modal')
                ->within('@cookie-consent-modal', function (Browser $modal): void {
                    $modal->assertVisible('@cookie-consent-cookie-policy-button')
                        ->assertSeeIn('@cookie-consent-refuse-button', 'Refuse cookies')
                        ->assertSeeIn('@cookie-consent-accept-button', 'Accept cookies');
                })
                ->click('@cookie-consent-cookie-policy-button')
                ->waitUntilMissing('@cookie-consent-modal')
                ->assertVisible('@cookie-policy-modal')
                ->within('@cookie-policy-modal', function (Browser $modal): void {
                    $modal->assertSee('Cookie Statement')
                        ->assertSeeIn('@cookie-policy-close-button', 'Close');
                })
                ->click('@cookie-policy-close-button')
                ->waitUntilMissing('@cookie-policy-modal')
                ->assertVisible('@cookie-consent-modal');
        });
    }

    public function testAcceptingCookies(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->deleteCookie(config('cookies.consent.cookie_name'))
                ->visitRoute('home')
                ->assertCookieMissing(config('cookies.consent.cookie_name'))
                ->assertVisible('@cookie-consent-modal')
                ->click('@cookie-consent-accept-button')
                ->waitUntilMissing('@cookie-consent-modal')
                ->assertHasCookie(config('cookies.consent.cookie_name'))
                ->assertCookieValue(config('cookies.consent.cookie_name'), config('cookies.consent.consent_value'));
        });
    }

    public function testRefusingCookies(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->deleteCookie(config('cookies.consent.cookie_name'))
                ->visitRoute('home')
                ->assertCookieMissing(config('cookies.consent.cookie_name'))
                ->assertVisible('@cookie-consent-modal')
                ->click('@cookie-consent-refuse-button')
                ->waitUntilMissing('@cookie-consent-modal')
                ->assertHasCookie(config('cookies.consent.cookie_name'))
                ->assertCookieValue(config('cookies.consent.cookie_name'), config('cookies.consent.refuse_value'));
        });
    }
}
