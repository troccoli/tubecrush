<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlternativePostSlug extends Model
{
    use HasFactory;
    use SluggableScopeHelpers;

    protected $fillable = [
        'slug',
        'post_id',
    ];
    protected string $slugKeyName = 'slug';

    public function getPostId(): int
    {
        return $this->post_id;
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
