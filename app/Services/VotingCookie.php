<?php

namespace App\Services;

use App\Contracts\VotingService;
use App\Models\Post;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

class VotingCookie implements VotingService
{
    private string $cookieName;
    private int $cookieLifetime;
    private array $votes;
    private CookieConsent $service;

    public function __construct(CookieConsent $service)
    {
        $this->cookieName = config('cookies.voting.cookie_name');
        $this->cookieLifetime = config('cookies.voting.cookie_lifetime');
        $this->service = $service;
        if ($this->service->consentHasBeenGiven()) {
            $this->votes = json_decode(Request::cookie($this->cookieName), true) ?? [];
            $this->refreshCookie();
        } else {
            $this->votes = [];
            $this->removeCookie();
        }
    }

    public function hasVoted(Post $post): bool
    {
        if ($this->service->consentHasBeenGiven()) {
            return in_array($post->getId(), $this->votes);
        }

        return false;
    }

    public function addVote(Post $post): void
    {
        if ($this->service->consentHasBeenGiven()) {
            $this->votes = array_unique(array_merge($this->votes, [$post->getId()]));
            $this->setVotes();
        }
    }

    public function removeVote(Post $post): void
    {
        if ($this->service->consentHasBeenGiven()) {
            $this->votes = array_values(array_diff($this->votes, [$post->getId()]));
            $this->setVotes();
        }
    }

    private function getVotes(): array
    {
        if (null === $this->votes) {
            $this->votes = json_decode(Request::cookie($this->cookieName), true) ?? [];
        }

        return $this->votes;
    }

    private function setVotes()
    {
        if (Cookie::hasQueued($this->cookieName)) {
            Cookie::unqueue($this->cookieName);
        }

        Cookie::queue($this->cookieName, json_encode($this->votes), $this->cookieLifetime);
    }

    private function refreshCookie()
    {
        $this->setVotes();
    }

    private function removeCookie()
    {
        Cookie::queue($this->cookieName, json_encode([]), 0);
    }
}
