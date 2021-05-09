<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\CookieConsent;
use Livewire\Livewire;
use Tests\Feature\TestCase;

class CookieConsentTest extends TestCase
{
    public function testItWillAskForConsentIfTheCookieDoesNotExist(): void
    {
        $this->app->bind(\App\Services\CookieConsent::class, function () {
            $mock = $this->getMockBuilder(\App\Services\CookieConsent::class)
                ->disableOriginalConstructor()
                ->onlyMethods(['cookieExists'])
                ->getMock();
            $mock->method('cookieExists')->willReturn(false);

            return $mock;
        });

        Livewire::test(CookieConsent::class)
            ->assertSet('askForConsent', true)
            ->assertSet('openConsentModal', true);
    }

    public function testItWillNotAskForConsentIfTheCookieExists(): void
    {
        $this->app->bind(\App\Services\CookieConsent::class, function () {
            $mock = $this->getMockBuilder(\App\Services\CookieConsent::class)
                ->disableOriginalConstructor()
                ->onlyMethods(['cookieExists'])
                ->getMock();
            $mock->method('cookieExists')->willReturn(true);

            return $mock;
        });

        Livewire::test(CookieConsent::class)
            ->assertSet('askForConsent', false)
            ->assertSet('openConsentModal', true);
    }

    public function testItGivesConsentWhenConsentIsGiven(): void
    {
        $service = $this->getMockBuilder(\App\Services\CookieConsent::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['giveConsent'])
            ->getMock();
        $service->expects(self::once())->method('giveConsent');

        Livewire::test(CookieConsent::class)
            ->call('giveConsent', $service)
            ->assertSet('askForConsent', false)
            ->assertSet('openConsentModal', false)
            ->assertEmitted('App\Http\Livewire\CookieConsent::cookieConsentGiven');
    }

    public function testItRefusesConsentWhenConsentIsRefused(): void
    {
        $service = $this->getMockBuilder(\App\Services\CookieConsent::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['refuseConsent'])
            ->getMock();
        $service->expects(self::once())->method('refuseConsent');

        Livewire::test(CookieConsent::class)
            ->call('refuseConsent', $service)
            ->assertSet('askForConsent', false)
            ->assertSet('openConsentModal', false)
            ->assertEmitted('App\Http\Livewire\CookieConsent::cookieConsentRefused');
    }
}
