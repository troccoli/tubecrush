<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
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

                    $elements = $this->elements($selector);
                    PHPUnit::assertCount(
                        $expectedCount,
                        $elements,
                        $message ?? "Failed asserting that actual size ".count($elements)." matches expected size $expectedCount for elements [{$selector}]."
                    );

                    return $this;
                });

            Browser::macro('withEach', function (string $selector, \Closure $callback) {
                /** @var Browser $this */

                if (Str::startsWith($selector, '@')) {
                    $selector = Str::replaceFirst('@', '[dusk="', $selector).'"]';
                }

                $elementsCount = count($this->elements($selector));

                for ($i = 1; $i <= $elementsCount; $i++) {
                    $this->within($selector.":nth-child({$i})", function ($browser) use ($callback) {
                        $callback($browser);
                    });
                }

                return $this;
            });
        }
    }
}
