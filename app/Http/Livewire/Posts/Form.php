<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

class Form extends Component
{
    public $currentPost;
    public $title;
    public $content;
    protected array $rules = [
        'title' => 'required|max:20',
        'content' => 'required|min:10|max:2000',
    ];

    public function mount(?int $postId = null)
    {
        if (null != $postId) {
            $currentPost = Post::findOrFail($postId);
            $this->currentPost = $currentPost;
            $this->title = $currentPost->getTitle();
            $this->content = $currentPost->getContent();
        }
    }

    public function render()
    {
        return view('livewire.posts.form');
    }

    public function submit()
    {
        $this->validate();

        if ($this->currentPost) {
            $this->currentPost->update([
                'title' => $this->title,
                'content' => $this->content,
            ]);
        } else {
            $post = Post::create([
                'title' => $this->title,
                'content' => $this->content,
                'author_id' => auth()->user()->getAuthIdentifier(),
            ]);

            session()->flash('new-post-id', $post->getId());
        }

        return $this->redirectBack();
    }

    public function redirectBack()
    {
        return redirect()->route('posts.list');
    }
}
