<?php

namespace WireComments\Components;

use App\Livewire\Forms\CreateComment;
use App\Livewire\Forms\EditComment;
use App\Models\Comment;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class CommentItem extends Component
{
    public Comment $comment;

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
        $this->authorize('reply', $this->comment);

        $this->replyForm->validate();

        $reply = $this->comment->children()->make($this->replyForm->only('body'));
        $reply->user()->associate(auth()->user());
        $reply->save();

        $this->dispatch('replied', $this->comment->id);

        $this->replyForm->reset();
    }

    /**
     * @throws AuthorizationException
     */
    public function delete(): void
    {
        $this->authorize('delete', $this->comment);
        $this->comment->delete();

        $this->deleted = true;
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function edit(): void
    {
        $this->authorize('edit', $this->comment);
        $this->editForm->validate();

        $this->comment->update($this->editForm->only('body'));

        $this->dispatch('edited', $this->comment->id);
    }

    public function render(): View
    {
        return view('wire-comments::livewire.components.comment-item');
    }
}
