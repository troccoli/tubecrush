<?php

namespace App\Contracts;

use App\Models\Post;

interface VotingService
{
    public function hasVoted(Post $post): bool;

    public function addVote(Post $post): void;

    public function removeVote(Post $post): void;
}
