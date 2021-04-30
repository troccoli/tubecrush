<?php

namespace Tests\Browser;

use Illuminate\Support\Str;
use PHPUnit\Framework\Assert as PHPUnit;

class Browser extends \Laravel\Dusk\Browser
{
    public function withEach(string $selector, \Closure $callback): self
    {
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
    }

    public function assertCountInElement(int $expectedCount, string $selector, string $message = null): self
    {
        $elements = $this->elements($selector);
        PHPUnit::assertCount(
            $expectedCount,
            $elements,
            $message ?? "Failed asserting that actual size ".count($elements)." matches expected size $expectedCount for elements [{$selector}]."
        );

        return $this;
    }
}
