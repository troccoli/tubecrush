<?php

namespace Tests\Feature\Livewire;

use App\Events\SomeoneHasContactedUs;
use App\Notifications\ContactUsMessage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\Feature\TestCase;

class ContactUsFormTest extends TestCase
{
    public function testTheComponentIsRendered(): void
    {
        $this->get(route('contact-us'))
            ->assertSeeLivewire('contact-us-form');
    }

    public function testTheNameIsMandatoryAndCannotBeLongerThan255Characters(): void
    {
        Event::fake();
        Notification::fake();

        Livewire::test('contact-us-form')
            ->call('contactUs')
            ->assertHasErrors(['name' => 'required'])
            ->set('name', Str::random(256))
            ->call('contactUs')
            ->assertHasErrors(['name' => 'max']);

        Event::assertNotDispatched(SomeoneHasContactedUs::class);
        Notification::assertNothingSent();
    }

    public function testTheEmailIsRequiredAndMustBeAValidEmail(): void
    {
        Event::fake();
        Notification::fake();

        Livewire::test('contact-us-form')
            ->call('contactUs')
            ->assertHasErrors(['email' => 'required'])
            ->set('email', 'email')
            ->call('contactUs')
            ->assertHasErrors(['email' => 'email']);

        Event::assertNotDispatched(SomeoneHasContactedUs::class);
        Notification::assertNothingSent();
    }

    public function testTheMessageIsRequiredAndMustBeAtLeast10CharactersLongAndNoMoreThan2000(): void
    {
        Event::fake();
        Notification::fake();

        Livewire::test('contact-us-form')
            ->call('contactUs')
            ->assertHasErrors(['message' => 'required'])
            ->set('message', '123456789')
            ->call('contactUs')
            ->assertHasErrors(['message' => 'min'])
            ->set('message', Str::random(2001))
            ->call('contactUs')
            ->assertHasErrors(['message' => 'max'])
            ->set('message', '1234567890')
            ->call('contactUs')
            ->assertHasNoErrors(['message' => 'min']);

        Event::assertNotDispatched(SomeoneHasContactedUs::class);
        Notification::assertNothingSent();
    }

    public function testSendsAnEmailWithTheMessage(): void
    {
        Notification::fake();

        Livewire::test('contact-us-form')
            ->set('name', 'John Doe')
            ->set('email', 'john.doe@example.com')
            ->set('message', 'Awesome site!')
            ->call('contactUs')
            ->assertHasNoErrors()
            ->assertSee('Your message has been sent.');

        Notification::assertSentTo(
            $this->superAdmin(),
            ContactUsMessage::class,
            function (ContactUsMessage $notification): bool {
                return $notification->getName() === 'John Doe'
                    && $notification->getEmail() === 'john.doe@example.com'
                    && $notification->getMessage() === 'Awesome site!';
            });
    }
}
