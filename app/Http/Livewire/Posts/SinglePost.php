<?php

namespace App\Http\Livewire\Posts;

use App\Contracts\VotingService;
use App\Models\Post;
use App\Services\CookieConsent;
use Livewire\Component;

class SinglePost extends Component
{
    /** @var Post */
    public $post;
    public bool $userCanVote = false;
    public bool $userHasVoted = false;

    protected $listeners = [
        \App\Http\Livewire\CookieConsent::EVENT_COOKIES_ACCEPTED => 'allowVoting',
        \App\Http\Livewire\CookieConsent::EVENT_COOKIES_DENIED => 'denyVoting',
    ];

    public function mount(CookieConsent $cookieService, VotingService $votingService)
    {
        $this->userCanVote = $cookieService->consentHasBeenGiven();
        if ($this->userCanVote) {
            $this->userHasVoted = $votingService->hasVoted($this->post);
        } else {
            $this->userHasVoted = false;
        }
    }

    public function render()
    {
        return view('livewire.posts.single-post');
    }

    public function allowVoting(VotingService $votingService)
    {
        $this->userCanVote = true;
        $this->userHasVoted = $votingService->hasVoted($this->post);
    }

    public function denyVoting()
    {
        $this->userCanVote = false;
        $this->userHasVoted = false;
    }

    public function vote(VotingService $votingService)
    {
        if ($this->userCanVote && !$this->userHasVoted) {
            $votingService->addVote($this->post);
            $this->post->incrementLikes();
            $this->post->refresh();
            $this->userHasVoted = true;
        }
    }

    public function unvote(VotingService $votingService)
    {
        if ($this->userCanVote && $this->userHasVoted) {
            $votingService->removeVote($this->post);
            $this->post->decrementLikes();
            $this->post->refresh();
            $this->userHasVoted = false;
        }
    }
}
