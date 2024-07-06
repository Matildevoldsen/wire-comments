<?php

namespace WireComments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Reaction
 *
 * @property int $comment_id
 * @property string $emoji
 * @property int $user_id
 * @property int $guest_id
 */
class Reaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'comment_id',
        'emoji',
        'guest_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }
}
