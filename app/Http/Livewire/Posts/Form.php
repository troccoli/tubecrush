<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class Form extends Component
{
    public $title;
    public $content;
    protected array $rules = [
        'title' => 'required|max:20',
        'content' => 'required|min:10|max:2000',
    ];

    public function render()
    {
        return view('livewire.posts.form');
    }

    public function createPost()
    {
        $this->validate();

        $post = Post::create([
            'title' => $this->title,
            'content' => $this->content,
            'author_id' => auth()->user()->getAuthIdentifier(),
        ]);

        session()->flash('new-post-id', $post->getId());

        return $this->redirectBack();
    }

    public function redirectBack()
    {
        return redirect()->route('posts.list');
    }
}
