<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use App\Models\Tag;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePost extends Component
{
    use WithFileUploads;

    public string $title = '';
    public int $line = 0;
    public string $content = '';
    public $photo;
    public ?string $photoCredit = null;
    public array $availableTags;
    public array $tags = [];

    protected array $rules = [
        'title' => 'required|max:20',
        'line' => 'exists:\App\Models\Line,id',
        'content' => 'required|min:10|max:2000',
        'photo' => 'required|mimes:jpg,jpeg,png|max:5120', // 5MB
        'photoCredit' => 'sometimes|max:20',
        'tags' => 'sometimes|array',
        'tags.*' => 'exists:\App\Models\Tag,id',
    ];

    public function mount()
    {
        $this->availableTags = Tag::query()->orderBy('slug')->get()
            ->map(function (Tag $tag): array {
                return [
                    'id' => $tag->getId(),
                    'text' => $tag->getName(),
                ];
            })->toArray();
    }

    public function updatedPhoto()
    {
        $this->validateOnly('photo');
    }

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
            'line_id' => $this->line,
            'content' => $this->content,
            'photo' => $this->photo->store('photos', 'public'),
            'photo_credit' => $this->photoCredit,
            'author_id' => auth()->user()->getAuthIdentifier(),
        ]);

        $post->tags()->sync($this->tags);

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
