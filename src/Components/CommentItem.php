<?php

namespace WireComments\Components;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use WireComments\Components\Forms\CreateComment;
use WireComments\Components\Forms\EditComment;
use WireComments\Models\Comment;

class CommentItem extends Component
{
    public int $depth = 0;
    public int $maxDepth = 3;
    public Comment $comment;
    public bool $allowGuests = false;

    public CreateComment $replyForm;

    public EditComment $editForm;

    public bool $deleted = false;

    public int $limit = 10;

    public array $emojis;

    public function mount(): void
    {
        $this->editForm->body = $this->comment->body;
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function reply(): void
    {
        $this->replyForm->validate();

        $guest_id = Cookie::get('guest_id') ?? Str::uuid();

        $reply = $this->comment->children()->make($this->replyForm->only('body'));
        if (auth()->user()) {
            $reply->user()->associate(auth()->user());
        } else {
            Cookie::queue('guest_id', $guest_id, 60 * 24 * 365);

            $reply->guest_id = $guest_id;
        }

        $reply->save();

        $this->dispatch('replied', $this->comment->id);

        $this->replyForm->reset();
    }

    /**
     * @throws AuthorizationException
     */
    public function delete(): void
    {
        if (!$this->authorizeGuest()) {
            $this->authorize('delete', $this->comment);
        }
        $this->comment->delete();

        $this->deleted = true;
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function edit(): void
    {
        if (!$this->authorizeGuest()) {
            $this->authorize('edit', $this->comment);
        }

        $this->editForm->validate();

        $this->comment->update($this->editForm->only('body'));

        $this->dispatch('edited', $this->comment->id);
    }

    public function authorizeGuest(): bool
    {
        if (!$this->allowGuests) {
            return false;
        }

        if (!Cookie::get('guest_id')) {
            return false;
        }

        return Cookie::get('guest_id') === $this->comment->guest_id;
    }

    public function render(): View
    {
        return view('wire-comments::livewire.components.comment-item');
    }
}
