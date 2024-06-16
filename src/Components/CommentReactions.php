<?php

namespace WireComments\Components;

use App\Models\Comment;
use App\Models\Reaction;
use Illuminate\View\View;
use Livewire\Component;

class CommentReactions extends Component
{
    public Comment $comment;
    public $reactions;
    public array $displayedEmojis;
    public array $presetEmojis;

    protected $rules = [
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
        $existingReaction = Reaction::where('user_id', auth()->id())
            ->where('comment_id', $this->comment->id)
            ->where('emoji', $emoji)
            ->first();

        if ($existingReaction) {
            $this->authorize('unreact', $existingReaction);
            $existingReaction->delete();
        } else {
            Reaction::create([
                'user_id' => auth()->id(),
                'comment_id' => $this->comment->id,
                'emoji' => $emoji,
            ]);
        }

        $this->reactions = $this->comment->reactions()->with('user')->get();
        $this->displayedEmojis = $this->reactions->pluck('emoji')->unique()->toArray();
    }

    public function getReactionCount($emoji): int|string
    {
        return $this->reactions->where('emoji', $emoji)->count() ?
            $this->reactions->where('emoji', $emoji)->count() : '';
    }

    public function hasUserReacted($emoji): bool
    {
        return $this->reactions->where('emoji', $emoji)->where('user_id', auth()->id())->count() > 0;
    }

    public function render(): View
    {
        return view('wire-comments::livewire.components.comment-reactions');
    }
}
