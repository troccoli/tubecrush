<?php

namespace App\Http\Livewire\Posts;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\Tag;
use App\Rules\UniquePostSlug;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePost extends Component
{
    use WithFileUploads;

    public array $availableTags;
    public string $content = '';
    public int $line = 0;
    public $photo;
    public ?string $photoCredit = null;
    public array $tags = [];
    public string $title = '';

    public function cancelCreate()
    {
        return $this->redirectBack();
    }

    private function redirectBack()
    {
        return redirect()->route('posts.list');
    }

    public function mount()
    {
        $this->availableTags = Tag::query()->orderBy('slug')->get()
            ->map(fn(Tag $tag) => ['id' => $tag->getKey(), 'text' => $tag->getName()])
            ->toArray();
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
            'status' => PostStatus::Draft,
            'published_at' => null,
        ]);

        $post->tags()->sync($this->tags);

        session()->flash('new-post-id', $post->getKey());

        return $this->redirectBack();
    }

    public function updatedPhoto()
    {
        $this->validateOnly('photo');
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'max:50', new UniquePostSlug()],
            'line' => 'exists:\App\Models\Line,id',
            'content' => 'required|min:10|max:2000',
            'photo' => 'required|mimes:jpg,jpeg,png|max:5120', // 5MB
            'photoCredit' => 'sometimes|max:20',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:\App\Models\Tag,id',
        ];
    }
}
