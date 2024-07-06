<?php

namespace WireComments\Components;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use WireComments\Models\Comment;
use WireComments\Models\Reaction;

class CommentReactions extends Component
{
    public Comment $comment;

    public $reactions;

    public bool $allowGuests = false;

    public array $displayedEmojis;

    public array $presetEmojis;

    protected array $rules = [
        'emoji' => 'required|string|max:255',
    ];

    public function mount(Comment $comment): void
    {
        $this->comment = $comment;
        $this->reactions = $comment->reactions()->with('user')->get();
        $this->displayedEmojis = $this->reactions->pluck('emoji')->unique()->toArray();
    }

    public function toggleReaction($emoji): void
    {
        $userId = auth()->id();
        $guest_id = Cookie::get('guest_id') ?? Str::uuid();
        $existingReaction = Reaction::where('comment_id', $this->comment->id)
            ->where('emoji', $emoji)
            ->when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when(! $userId && $guest_id, function ($query) use ($guest_id) {
                return $query->where('guest_id', $guest_id);
            })
            ->first();

        if ($existingReaction) {
            $this->authorizeReaction($existingReaction);
            $existingReaction->delete();
        } else {
            if (auth()->user()) {
                Reaction::create([
                    'user_id' => auth()->id() ?? null,
                    'comment_id' => $this->comment->id,
                    'emoji' => $emoji,
                ]);
            } else {
                Cookie::queue('guest_id', $guest_id, 60 * 24 * 365);

                Reaction::create([
                    'comment_id' => $this->comment->id,
                    'emoji' => $emoji,
                    'guest_id' => $guest_id,
                ]);
            }
        }

        $this->reactions = $this->comment->reactions()->with('user')->get();
        $this->displayedEmojis = $this->reactions->pluck('emoji')->unique()->toArray();
    }

    public function authorizeGuest(): bool
    {
        if (! $this->allowGuests) {
            return false;
        }

        if (! Cookie::get('guest_id')) {
            return false;
        }

        return (string) Cookie::get('guest_id') === (string) $this->comment->guest_id;
    }

    /**
     * @throws AuthorizationException
     */
    private function authorizeReaction(Reaction $existingReaction): bool|\Illuminate\Auth\Access\Response
    {
        if (! $this->allowGuests) {
            return false;
        }

        if ((string) $existingReaction->guest_id === (string) Cookie::get('guest_id')) {
            return true;
        }

        return $this->authorize('unreact', $existingReaction);
    }

    public function getReactionCount($emoji): int|string
    {
        return $this->reactions->where('emoji', $emoji)->count() ?
            $this->reactions->where('emoji', $emoji)->count() : '';
    }

    public function hasUserReacted($emoji): bool
    {
        $userId = auth()->id();
        $guestId = Cookie::get('guest_id'); // Use Cookie::get() to retrieve the guest_id

        if ($userId) {
            // Check reactions for authenticated user
            return $this->reactions->where('emoji', $emoji)
                ->where('user_id', $userId)
                ->count() > 0;
        } elseif ($guestId) {
            // Check reactions for guest with a valid guest_id
            return $this->reactions->where('emoji', $emoji)
                ->where('guest_id', $guestId)
                ->count() > 0;
        }

        // If no user_id and no valid guest_id, return false
        return false;
    }

    public function render(): View
    {
        return view('wire-comments::livewire.components.comment-reactions');
    }
}
