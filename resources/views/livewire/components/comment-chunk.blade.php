<div>
    @foreach($comments as $comment)
        <div class="border-b border-gray-100 last:border-b-0" wire:key="item-{{ md5($comment->id) }}">
            <livewire:comment-item :emojis="$emojis" :comment="$comment" :key="$comment->id" />
        </div>
    @endforeach
</div>
