<?php

namespace WireComments\Traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Commentable
{
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
