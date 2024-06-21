<div>
    @foreach($comments as $comment)
        <div class="border-b dark:border-none border-gray-100 last:border-b-0" wire:key="item-{{ md5($comment->id) }}">
            <livewire:comment-item :allowGuests="$allowGuests" :maxDepth="$maxDepth" :emojis="$emojis" :comment="$comment" :key="$comment->id" />
        </div>
    @endforeach
</div>
