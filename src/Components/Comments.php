<?php

namespace WireComments\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use WireComments\Components\Forms\CreateComment;

class Comments extends Component
{
    public Model $model;

    public CreateComment $form;

    public int $page = 1;

    public array $emojis;

    public array $chunks = [];

    public function mount(): void
    {
        $this->chunks = $this->model->comments()
            ->latest()
            ->pluck('id')
            ->chunk(10)
            ->toArray();
    }

    #[Computed()]
    public function commentsCount(): int
    {
        return array_sum(array_map('count', $this->chunks));
    }

    public function loadMore(): void
    {
        if (! $this->hasMorePages()) {
            return;
        }

        $this->page++;
    }

    public function hasMorePages(): bool
    {
        return $this->page < count($this->chunks);
    }

    /**
     * @throws ValidationException
     */
    public function createComment(): void
    {
        $this->form->validate();

        $comment = $this->model->comments()->make($this->form->only('body'));
        $comment->user()->associate(auth()->user());

        $this->form->reset();

        $comment->save();

        if (count($this->chunks) === 0) {
            $this->chunks[] = [];
        }

        array_unshift($this->chunks[0], $comment->id);
    }

    public function render(): View
    {
        return view('wire-comments::livewire.components.comments');
    }
}
