<?php

namespace WireComments\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use WireComments\Models\Comment;

trait Commentable
{
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
