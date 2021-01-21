<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class DashboardList extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.posts.dashboard-list', [
            'posts' => Post::query()->orderByDesc('created_at')->paginate(5),
        ]);
    }
}
