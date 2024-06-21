<?php

namespace WireComments\Components;

use Illuminate\View\View;
use Livewire\Component;
use WireComments\Models\Comment;

class CommentChunk extends Component
{
    public array $ids = [];

    public int $maxDepth = 3;

    public bool $allowGuests = false;

    public array $emojis;

    public function render(): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {
        $orderClause = 'CASE ';
        foreach ($this->ids as $index => $id) {
            $orderClause .= "WHEN id = $id THEN $index ";
        }
        $orderClause .= 'END';

        return view('wire-comments::livewire.components.comment-chunk', [
            'comments' => Comment::query()
                ->whereIn('id', $this->ids)
                ->with([
                    'user',
                    'children' => function ($query) {
                        $query->oldest()->with('user');
                    },
                ])
                ->orderByRaw($orderClause)
                ->latest()
                ->get(),
        ]);
    }
}
