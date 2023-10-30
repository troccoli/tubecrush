<?php

namespace App\Models;

use App\Builders\PostBuilder;
use App\Enums\PostStatus;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static PostFactory factory()
 * @method static PostBuilder query()
 */
class Post extends Model
{
    use HasFactory;
    use Sluggable;
    use SluggableScopeHelpers;
    use SoftDeletes;

    protected $casts = [
        'status' => PostStatus::class,
        'published_at' => 'datetime',
    ];
    protected $fillable = [
        'title',
        'line_id',
        'content',
        'photo',
        'photo_credit',
        'author_id',
        'status',
        'published_at',
    ];

    protected static function booted()
    {
        static::updated(function (Post $post) {
            if (array_key_exists('slug', $post->getDirty())) {
                AlternativePostSlug::create([
                    'slug' => $post->getOriginal('slug'),
                    'post_id' => $post->getKey(),
                ]);
            }
        });
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return PostBuilder
     */
    public function newEloquentBuilder($query): PostBuilder
    {
        return new PostBuilder($query);
    }

    public function alternativeSlugs(): HasMany
    {
        return $this->hasMany(AlternativePostSlug::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function decrementLikes(int $amount = 1): self
    {
        $this->decrement('likes', $amount);

        return $this;
    }

    public function getAuthorName(): string
    {
        return $this->author->getName();
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getLikes(): int
    {
        return $this->likes;
    }

    public function getLine(): Line
    {
        return $this->line;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function getPhotoCredit(): ?string
    {
        return $this->photo_credit;
    }

    public function getCreationDate(): Carbon
    {
        return $this->created_at;
    }

    public function getPublishedDate(): ?Carbon
    {
        return $this->published_at;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function incrementLikes(int $amount = 1): self
    {
        $this->increment('likes', $amount);

        return $this;
    }

    public function line(): BelongsTo
    {
        return $this->belongsTo(Line::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getStatus(): PostStatus
    {
        return $this->status;
    }

    public function isDraft(): bool
    {
        return $this->status === PostStatus::Draft;
    }

    public function publish(): self
    {
        $this->status = PostStatus::Published;
        $this->published_at = now();

        $this->save();

        return $this->refresh();
    }

    public function unpublish(): self
    {
        $this->status = PostStatus::Draft;
        $this->published_at = null;

        $this->save();

        return $this->refresh();
    }
}
