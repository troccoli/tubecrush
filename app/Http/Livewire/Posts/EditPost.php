<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

class EditPost extends Component
{
    public Post $post;
    public string $title;
    public string $content;
    protected array $rules = [
        'title' => 'required|max:20',
        'content' => 'required|min:10|max:2000',
    ];

    public function mount(int $postId)
    {
        $this->post = Post::findOrFail($postId);
        $this->title = $this->post->getTitle();
        $this->content = $this->post->getContent();
    }

    public function render()
    {
        return view('livewire.posts.edit-post');
    }

    public function submit()
    {
        $this->validate();

        $this->post->update([
            'title' => $this->title,
            'content' => $this->content,
        ]);

        return $this->redirectBack();
    }

    public function cancelEdit()
    {
        return $this->redirectBack();
    }

    private function redirectBack()
    {
        return redirect()->route('posts.list');
    }
}
