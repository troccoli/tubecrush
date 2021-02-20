<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPost extends Component
{
    use WithFileUploads;

    public Post $post;
    public string $title;
    public int $line = 0;
    public string $content;
    public $photo;
    public ?string $photoCredit = null;
    protected array $rules = [
        'title' => 'required|max:20',
        'line' => 'exists:\App\Models\Line,id',
        'content' => 'required|min:10|max:2000',
        'photo' => 'nullable|sometimes|mimes:jpg,jpeg,png|max:5120', // 5MB
        'photoCredit' => 'sometimes|max:20',
    ];

    public function mount(int $postId)
    {
        $this->post = Post::findOrFail($postId);
        $this->title = $this->post->getTitle();
        $this->line = $this->post->getLine()->getId();
        $this->content = $this->post->getContent();
        $this->photoCredit = $this->post->getPhotoCredit();
    }

    public function updatedPhoto()
    {
        $this->validateOnly('photo');
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
            'line_id' => $this->line,
            'content' => $this->content,
            'photo' => $this->photo ? $this->photo->store('photos', 'public') : $this->post->getPhoto(),
            'photo_credit' => $this->photoCredit,
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
