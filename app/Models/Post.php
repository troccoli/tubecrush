<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'line_id', 'content', 'photo', 'photo_credit', 'author_id'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function line(): BelongsTo
    {
        return $this->belongsTo(Line::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getPublishedDate(): Carbon
    {
        return $this->created_at;
    }

    public function getAuthorName(): string
    {
        return $this->author->getName();
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function getPhotoCredit(): ?string
    {
        return $this->photo_credit;
    }

    public function getLine(): Line
    {
        return $this->line;
    }
}
