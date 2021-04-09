<?php

namespace App\Http\Livewire\Posts;

use App\Models\Line;
use App\Models\Post;
use Livewire\Component;

class ListPosts extends Component
{
    private const POST_PER_PAGE = 3;
    public $posts;
    public $count;
    public ?int $lineId = null;

    public function mount(?string $lineId = null)
    {
        if ($lineId) {
            $this->lineId = $lineId;
        }
        $this->count = self::POST_PER_PAGE;
        $this->loadPosts();
    }

    public function render()
    {
        return view('livewire.posts.list-posts');
    }

    public function loadMorePosts(): void
    {
        $this->count += self::POST_PER_PAGE;
        $this->loadPosts();
    }

    private function loadPosts(): void
    {
        $query = Post::query()
            ->with(['author', 'line', 'tags'])
            ->orderByDesc('created_at')
            ->limit($this->count);

        if ($this->lineId) {
            $query->onLine($this->lineId);
        }

        $this->posts = $query->get();
    }
}