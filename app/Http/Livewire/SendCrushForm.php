<?php

namespace App\Http\Livewire;

use App\Enums\PostStatus;
use App\Enums\UserRoles;
use App\Events\NewTubeCrushSubmitted;
use App\Models\Post;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class SendCrushForm extends Component
{
    use WithFileUploads;

    public int $line = 0;
    public $photo;
    public ?string $photoCredit = null;

    public function render()
    {
        return view('livewire.send-crush-form');
    }

    public function clearForm()
    {
        $this->line = 0;
        $this->photo = null;
        $this->photoCredit = null;
    }

    public function submit()
    {
        $this->validate();

        /** @var Post $post */
        $post = Post::query()->create([
            'title' => 'New TubeCrush submitted',
            'content' => '',
            'line_id' => $this->line,
            'photo' => $this->photo->store('photos', 'public'),
            'photo_credit' => $this->photoCredit,
            'author_id' => User::query()->role(UserRoles::Editor->value)->first()->getKey(),
            'status' => PostStatus::Draft,
            'published_at' => null,
        ]);

        NewTubeCrushSubmitted::dispatch($post);

        $this->redirectRoute('send-crush-success');
    }

    public function updatedPhoto()
    {
        $this->validateOnly('photo');
    }

    protected function rules(): array
    {
        return [
            'line' => 'exists:\App\Models\Line,id',
            'photo' => 'required|mimes:jpg,jpeg,png|max:5120', // 5MB
            'photoCredit' => 'sometimes|max:20',
        ];
    }
}
