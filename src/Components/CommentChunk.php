<?php

namespace WireComments\Components;

use WireComments\Models\Comment;
use Illuminate\View\View;
use Livewire\Component;

class CommentChunk extends Component
{
    public array $ids = [];

    public array $emojis;

    public function render(): View
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
