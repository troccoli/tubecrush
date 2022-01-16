<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use App\Models\Tag;
use App\Rules\UniquePostSlug;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPost extends Component
{
    use WithFileUploads;

    public Post $post;
    public string $title;
    public string $slug;
    public int $line = 0;
    public string $content;
    public $photo;
    public ?string $photoCredit = null;
    public array $availableTags;
    public array $tags = [];

    public function mount(int $postId)
    {
        $this->availableTags = Tag::query()->orderBy('slug')->get()
            ->map(function (Tag $tag): array {
                return [
                    'id' => $tag->getId(),
                    'text' => $tag->getName(),
                ];
            })->toArray();

        $this->post = Post::findOrFail($postId);
        $this->title = $this->post->getTitle();
        $this->slug = $this->post->getSlug();
        $this->line = $this->post->getLine()->getId();
        $this->content = $this->post->getContent();
        $this->photoCredit = $this->post->getPhotoCredit();
        $this->tags = $this->post->tags()->pluck('id')->toArray();
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

        $this->post->tags()->sync($this->tags);

        return $this->redirectBack();
    }

    private function redirectBack()
    {
        return redirect()->route('posts.list');
    }

    public function cancelEdit()
    {
        return $this->redirectBack();
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'max:50', new UniquePostSlug($this->post)],
            'line' => 'exists:\App\Models\Line,id',
            'content' => 'required|min:10|max:2000',
            'photo' => 'nullable|sometimes|mimes:jpg,jpeg,png|max:5120', // 5MB
            'photoCredit' => 'sometimes|max:20',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:\App\Models\Tag,id',
        ];
    }
}
