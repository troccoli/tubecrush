<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Assert as PHPUnit;

class DuskServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (!$this->app->environment('production')) {
            Browser::macro('assertCountInElement',
                function (int $expectedCount, string $selector, string $message = null): Browser {
                    /** @var Browser $this */

                    $elements = $this->resolver->all($selector);
                    PHPUnit::assertCount(
                        $expectedCount,
                        $elements,
                        $message ?? "Failed asserting that actual size ".count($elements)." matches expected size $expectedCount for elements [{$selector}]."
                    );

                    return $this;
                });
        }
    }
}
