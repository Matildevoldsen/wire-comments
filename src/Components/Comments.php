<?php

namespace WireComments\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use WireComments\Components\Forms\CreateComment;

class Comments extends Component
{
    public Model $model;

    public CreateComment $form;

    public int $maxDepth = 3;

    public int $page = 1;

    public array $emojis;

    public array $chunks = [];

    public bool $allowGuests = false;

    public int $articlesLimit = 10;

    public function mount(): void
    {
        $this->chunks = $this->model->comments()
            ->latest()
            ->pluck('id')
            ->chunk($this->articlesLimit)
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

        $guest_id = Cookie::get('guest_id') ?? Str::uuid();
        if (auth()->user()) {
            $comment->user()->associate(auth()->user());
        } else {
            Cookie::queue('guest_id', $guest_id, 60 * 24 * 365);
        }

        $comment->guest_id = $guest_id;

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
