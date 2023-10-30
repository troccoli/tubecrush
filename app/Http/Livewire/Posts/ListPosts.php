<?php

namespace App\Http\Livewire\Posts;

use App\Builders\PostBuilder;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ListPosts extends Component
{
    private const POST_PER_PAGE = 3;
    public $posts;
    public $count;
    public ?int $lineId = null;
    public ?int $tagId = null;

    public function mount(?string $lineId = null, ?string $tagId = null)
    {
        if ($lineId) {
            $this->lineId = $lineId;
        } elseif ($tagId) {
            $this->tagId = $tagId;
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
            ->when($this->lineId, function (PostBuilder $query): PostBuilder {
                return $query->onLine($this->lineId);
            })
            ->when($this->tagId, function (PostBuilder $query): PostBuilder {
                return $query->withTag($this->tagId);
            })
            ->published()
            ->latest('published_at')
            ->limit($this->count);

        $this->posts = $query->get();

        $this->emit('morePostsLoaded');
    }
}
