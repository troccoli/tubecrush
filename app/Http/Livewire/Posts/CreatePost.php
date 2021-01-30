<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

class CreatePost extends Component
{
    public string $title = '';
    public string $content = '';
    protected array $rules = [
        'title' => 'required|max:20',
        'content' => 'required|min:10|max:2000',
    ];

    public function render()
    {
        return view('livewire.posts.create-post');
    }

    public function submit()
    {
        $this->validate();

        /** @var Post $post */
        $post = Post::query()->create([
            'title' => $this->title,
            'content' => $this->content,
            'author_id' => auth()->user()->getAuthIdentifier(),
        ]);

        session()->flash('new-post-id', $post->getId());

        return $this->redirectBack();
    }

    public function cancelCreate()
    {
        return $this->redirectBack();
    }

    private function redirectBack()
    {
        return redirect()->route('posts.list');
    }
}
