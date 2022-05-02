<?php

namespace Tests\Feature\Livewire\Posts;

use App\Contracts\VotingService;
use App\Http\Livewire\CookieConsent;
use App\Http\Livewire\Posts\SinglePost;
use App\Models\Line;
use App\Models\Post;
use App\Models\Tag;
use App\Services\VotingCookie;
use Livewire\Livewire;
use Tests\Feature\TestCase;

class SinglePostTest extends TestCase
{
    public function testTheComponentIsRenderedOnTheHomepage(): void
    {
        $this->get(route('home'))
            ->assertSeeLivewire('posts.single-post');
    }

    public function testTheComponentIsRenderedOnTheLinesPage(): void
    {
        foreach (Line::all() as $line) {
            if ($line->posts->isEmpty()) {
                continue;
            }
            $this->get(route('posts-by-lines', ['slug' => $line->getSlug()]))
                ->assertSeeLivewire('posts.single-post');
        }
    }

    public function testTheComponentIsRenderedOnTheTagsPage(): void
    {
        foreach (Tag::all() as $tag) {
            if ($tag->posts->isEmpty()) {
                continue;
            }
            $this->get(route('posts-by-tags', ['slug' => $tag->getSlug()]))
                ->assertSeeLivewire('posts.single-post');
        }
    }

    /**
     * @dataProvider cookiesValuesDataProvider
     */
    public function testItAllowsVotingIfConsentHasBeenGiven(
        bool $consentHasBeenGiven,
        bool $userHasVoted,
        bool $expectedUserCanVote,
        bool $expectedUserHasVoted
    ): void {
        $post = Post::latest()->first();

        $this->app->bind(\App\Services\CookieConsent::class, function () use ($consentHasBeenGiven) {
            $mock = $this->getMockBuilder(\App\Services\CookieConsent::class)
                ->disableOriginalConstructor()
                ->onlyMethods(['consentHasBeenGiven'])
                ->getMock();
            $mock->method('consentHasBeenGiven')->willReturn($consentHasBeenGiven);

            return $mock;
        });

        $this->app->singleton(VotingService::class, function () use ($userHasVoted) {
            $votingCookie = $this->getMockBuilder(VotingCookie::class)
                ->disableOriginalConstructor()
                ->onlyMethods(['hasVoted'])
                ->getMock();
            $votingCookie->method('hasVoted')->willReturn($userHasVoted);

            return $votingCookie;
        });

        Livewire::test(SinglePost::class, ['post' => $post])
            ->assertSet('userCanVote', $expectedUserCanVote)
            ->assertSet('userHasVoted', $expectedUserHasVoted);
    }

    public function cookiesValuesDataProvider(): array
    {
        return [
            'User has not given consent (1)' => [
                'consentHasBeenGiven' => false,
                'userHasVoted' => true,
                'expectedUserCanVote' => false,
                'expectedUserHasVoted' => false,
            ],
            'User has not given consent (2)' => [
                'consentHasBeenGiven' => false,
                'userHasVoted' => false,
                'expectedUserCanVote' => false,
                'expectedUserHasVoted' => false,
            ],
            'User has given consent but has not voted yet' => [
                'consentHasBeenGiven' => true,
                'userHasVoted' => false,
                'expectedUserCanVote' => true,
                'expectedUserHasVoted' => false,
            ],
            'User has given consent and already voted' => [
                'consentHasBeenGiven' => true,
                'userHasVoted' => true,
                'expectedUserCanVote' => true,
                'expectedUserHasVoted' => true,
            ],
        ];
    }

    public function testItSetsUserCanVoteWhenCookiesHaveBeenAccepted(): void
    {
        $post = Post::latest()->first();

        Livewire::test(SinglePost::class, ['post' => $post])
            ->emit(CookieConsent::EVENT_COOKIES_ACCEPTED)
            ->assertSet('userCanVote', true);
    }

    public function testItSetsUserCannotVoteWhenCookiesHaveBeenRefused(): void
    {
        $post = Post::latest()->first();

        Livewire::test(SinglePost::class, ['post' => $post])
            ->emit(CookieConsent::EVENT_COOKIES_DENIED)
            ->assertSet('userCanVote', false);
    }

    public function testItSetsUserHasVotedWhenCookiesHaveBeenAccepted(): void
    {
        $post = Post::latest()->first();
        $this->app->singleton(VotingService::class, function () {
            $votingCookie = $this->getMockBuilder(VotingCookie::class)
                ->disableOriginalConstructor()
                ->onlyMethods(['hasVoted'])
                ->getMock();
            $votingCookie->method('hasVoted')->willReturnOnConsecutiveCalls(true, false);

            return $votingCookie;
        });

        Livewire::test(SinglePost::class, ['post' => $post])
            ->emit(CookieConsent::EVENT_COOKIES_ACCEPTED)
            ->assertSet('userHasVoted', true)
            ->emit(CookieConsent::EVENT_COOKIES_ACCEPTED)
            ->assertSet('userHasVoted', false);
    }

    public function testItSetsUserHasNotVotedWhenCookiesHaveBeenRefused(): void
    {
        $post = Post::latest()->first();

        Livewire::test(SinglePost::class, ['post' => $post])
            ->emit(CookieConsent::EVENT_COOKIES_DENIED)
            ->assertSet('userHasVoted', false);
    }

    /**
     * @dataProvider incrementVoteDataProvider
     */
    public function testItIncrementsTheVotes(
        bool $userCanVote,
        bool $userHasVoted,
        int $expectedAddVoteCallCount,
        int $expectedIncrement,
        bool $expectedUserHasVoted
    ): void {
        /** @var Post $post */
        $post = Post::latest()->first();
        $votes = $post->getLikes();
        $votingCookie = $this->getMockBuilder(VotingCookie::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['addVote'])
            ->getMock();
        $votingCookie->expects(self::exactly($expectedAddVoteCallCount))->method('addVote');

        Livewire::test(SinglePost::class, ['post' => $post])
            ->set('userCanVote', $userCanVote)
            ->set('userHasVoted', $userHasVoted)
            ->call('vote', $votingCookie)
            ->assertSet('userHasVoted', $expectedUserHasVoted);

        $post->refresh();
        $this->assertEquals($votes + $expectedIncrement, $post->getLikes());
    }

    public function incrementVoteDataProvider(): array
    {
        return [
            'User cannot vote (1)' => [
                'userCanVote' => false,
                'userHasVoted' => true,
                'expectedAddVoteCallCount' => 0,
                'expectedIncrement' => 0,
                'expectedUserHasVoted' => true,
            ],
            'User cannot vote (2)' => [
                'userCanVote' => false,
                'userHasVoted' => false,
                'expectedAddVoteCallCount' => 0,
                'expectedIncrement' => 0,
                'expectedUserHasVoted' => false,
            ],
            'User can vote but has not voted yet' => [
                'userCanVote' => true,
                'userHasVoted' => false,
                'expectedAddVoteCallCount' => 1,
                'expectedIncrement' => 1,
                'expectedUserHasVoted' => true,
            ],
            'User can vote and has already voted' => [
                'userCanVote' => true,
                'userHasVoted' => true,
                'expectedAddVoteCallCount' => 0,
                'expectedIncrement' => 0,
                'expectedUserHasVoted' => true,
            ],
        ];
    }

    /**
     * @dataProvider decrementVoteDataProvider
     */
    public function testItDecrementsTheVotes(
        bool $userCanVote,
        bool $userHasVoted,
        int $expectedRemoveVoteCallCount,
        int $expectedDecrement,
        bool $expectedUserHasVoted
    ): void {
        /** @var Post $post */
        $post = Post::latest()->first();
        $votes = $post->getLikes();
        $votingCookie = $this->getMockBuilder(VotingCookie::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['removeVote'])
            ->getMock();
        $votingCookie->expects(self::exactly($expectedRemoveVoteCallCount))->method('removeVote');

        Livewire::test(SinglePost::class, ['post' => $post])
            ->set('userCanVote', $userCanVote)
            ->set('userHasVoted', $userHasVoted)
            ->call('unvote', $votingCookie)
            ->assertSet('userHasVoted', $expectedUserHasVoted);

        $post->refresh();
        $this->assertEquals($votes - $expectedDecrement, $post->getLikes());
    }

    public function decrementVoteDataProvider(): array
    {
        return [
            'User cannot vote (1)' => [
                'userCanVote' => false,
                'userHasVoted' => true,
                'expectedRemoveVoteCallCount' => 0,
                'expectedDecrement' => 0,
                'expectedUserHasVoted' => true,
            ],
            'User cannot vote (2)' => [
                'userCanVote' => false,
                'userHasVoted' => false,
                'expectedRemoveVoteCallCount' => 0,
                'expectedDecrement' => 0,
                'expectedUserHasVoted' => false,
            ],
            'User can vote but has not voted yet' => [
                'userCanVote' => true,
                'userHasVoted' => false,
                'expectedRemoveVoteCallCount' => 0,
                'expectedDecrement' => 0,
                'expectedUserHasVoted' => false,
            ],
            'User can vote and has already voted' => [
                'userCanVote' => true,
                'userHasVoted' => true,
                'expectedRemoveVoteCallCount' => 1,
                'expectedDecrement' => 1,
                'expectedUserHasVoted' => false,
            ],
        ];
    }
}
