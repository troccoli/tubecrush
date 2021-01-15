<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

class HomepageList extends Component
{
    public $posts;
    public $count;

    private const POST_PER_PAGE = 3;

    public function mount()
    {
        $this->count = self::POST_PER_PAGE;
        $this->loadPosts();
    }

    public function render()
    {
        return view('livewire.posts.homepage-list');
    }

    public function loadMorePosts(): void
    {
        $this->count += self::POST_PER_PAGE;
        $this->loadPosts();
    }

    private function loadPosts(): void
    {
        $this->posts = Post::query()->orderByDesc('created_at')->limit($this->count)->get();
    }
}
