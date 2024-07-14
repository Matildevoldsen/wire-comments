<?php

namespace WireComments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\LaravelMarkdown\MarkdownRenderer;
use Stevebauman\Purify\Facades\Purify;

/**
 * Class Comment
 *
 * @property string $body
 * @property int $guest_id
 * @property int $parent_id
 * @property int $user_id
 */
class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'body',
        'parent_id',
        'guest_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function getComment(): string
    {
        $body = Purify::clean($this->body);

        return app(MarkdownRenderer::class)
            ->toHtml($this->body);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }
}
