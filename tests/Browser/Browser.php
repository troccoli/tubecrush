<?php

namespace Tests\Browser;

use Closure;
use Illuminate\Support\Str;
use PHPUnit\Framework\Assert as PHPUnit;

class Browser extends \Laravel\Dusk\Browser
{
    public function acceptCookies(): self
    {
        $this->visitRoute('dusk.cookies-consent')
            ->cookie(config('cookies.consent.cookie_name'), config('cookies.consent.consent_value'));

        return $this;
    }

    public function refuseCookies(): self
    {
        $this->visitRoute('dusk.cookies-consent')
            ->cookie(config('cookies.consent.cookie_name'), config('cookies.consent.refuse_value'));

        return $this;
    }

    public function withEach(string $selector, Closure $callback): self
    {
        if (Str::startsWith($selector, '@')) {
            $selector = Str::replaceFirst('@', '[dusk="', $selector) . '"]';
        }

        $elementsCount = count($this->elements($selector));

        for ($i = 1; $i <= $elementsCount; $i++) {
            $this->within($selector . ":nth-child({$i})", function ($browser) use ($callback, $i) {
                $callback($browser, $i);
            });
        }

        return $this;
    }

    public function assertCountInElement(int $expectedCount, string $selector, string $message = null): self
    {
        $elements = $this->elements($selector);
        PHPUnit::assertCount(
            $expectedCount,
            $elements,
            $message ?? 'Failed asserting that actual size ' . count(
                $elements
            ) . " matches expected size $expectedCount for elements [{$selector}]."
        );

        return $this;
    }
}
