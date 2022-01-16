<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use Illuminate\Http\Response;
use Livewire\Component;
use Livewire\WithPagination;

class DashboardList extends Component
{
    use WithPagination;

    public ?int $confirmingId = null;
    public string $confirmingTitle = '';

    public function render()
    {
        return view('livewire.posts.dashboard-list', [
            'posts' => Post::query()->orderByDesc('created_at')->paginate(5),
        ]);
    }

    public function confirmDelete(Post $post)
    {
        $this->confirmingId = $post->getKey();
        $this->confirmingTitle = $post->getTitle();
    }

    public function deletePost()
    {
        abort_unless(auth()->user()->can('delete posts'), Response::HTTP_UNAUTHORIZED);

        Post::destroy($this->confirmingId);
        $this->confirmingId = null;
    }

    public function keepPost()
    {
        $this->confirmingId = null;
    }
}
