<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use Illuminate\Http\Response;
use Livewire\Component;
use Livewire\WithPagination;

class DashboardList extends Component
{
    use WithPagination;

    public ?int $confirmingDeletingId = null;
    public ?int $confirmingPublishingId = null;
    public ?int $confirmingUnpublishingId = null;
    public string $confirmingTitle = '';

    public function render()
    {
        return view('livewire.posts.dashboard-list', [
            'posts' => Post::query()->latest()->paginate(5),
        ]);
    }

    public function confirmAction(Post $post, string $action)
    {
        match ($action) {
            'delete' => $this->confirmingDeletingId = $post->getId(),
            'publish' => $this->confirmingPublishingId = $post->getKey(),
            'unpublish' => $this->confirmingUnpublishingId = $post->getId(),
        };
        $this->confirmingTitle = $post->getTitle();
    }

    public function deletePost()
    {
        abort_unless(auth()->user()->can('delete posts'), Response::HTTP_UNAUTHORIZED);

        Post::destroy($this->confirmingDeletingId);
        $this->confirmingDeletingId = null;
    }

    public function publishPost()
    {
        abort_unless(auth()->user()->can('publish posts'), Response::HTTP_UNAUTHORIZED);

        Post::findOrFail($this->confirmingPublishingId)->publish();
        $this->confirmingPublishingId = null;
    }

    public function unpublishPost()
    {
        abort_unless(auth()->user()->can('publish posts'), Response::HTTP_UNAUTHORIZED);

        Post::findOrFail($this->confirmingUnpublishingId)->unpublish();
        $this->confirmingUnpublishingId = null;
    }

    public function keepPost()
    {
        $this->confirmingDeletingId = null;
        $this->confirmingPublishingId = null;
        $this->confirmingUnpublishingId = null;
    }
}
