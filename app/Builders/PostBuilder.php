<?php

namespace App\Builders;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Builder;

class PostBuilder extends Builder
{
    public function published(): self
    {
        return $this->where('status', PostStatus::Published);
    }

    public function onLine(int $lineId): self
    {
        return $this->where('line_id', $lineId);
    }

    public function withTag(int $tagId): self
    {
        return $this->whereHas('tags', function (Builder $query) use ($tagId): Builder {
            return $query->where('id', $tagId);
        });
    }
}
