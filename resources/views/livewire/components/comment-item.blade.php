<div id="{{ $comment->id  }}">
    @if (!$deleted)
        <div
            x-on:replied.window="replying = false;"
            x-on:edited.window="editing = false;"
            x-data="{ replying: false, editing: false }"
            class="my-6">
            <div>
                <div class="flex items-center space-x-2">
                    <img src="{{ $comment->user->profile_photo_url ?? 'https://ui-avatars.com/api/name=du' }}"
                         alt="{{ $comment->user->name ?? '' }}"
                         class="size-8 bg-black rounded-full"/>
                    <div class="font-semibold">
                        {{ $comment->user->name ?? '[deleted user]' }}
                    </div>
                    <div class="text-sm" x-human-date datetime="{{ $comment->created_at->toDateTimeString() }}"></div>
                </div>
                @can('edit', $comment)
                    <template x-if="editing">
                        <form wire:submit="edit" class="mt-4">
                            <div class="mb-4">
                                <x-markdown-editor :options="['b', 'i', 'h1', 'h2', 'ul', 'ol']" wire:model="editForm.body" placeholder="Post a comment" class="w-full"
                                                   rows="4"/>

                                @error('editForm.body')
                                    <p class="text-red-500 mt-1 mb-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                Save
                            </button>
                            <button class="ml-2 text-sm text-gray-500" x-on:click="editing = false">Cancel</button>
                        </form>
                    </template>
                @endcan
                <div x-show="!editing" class="mt-4">
                    @markdown{!! $comment->body !!}@endmarkdown
                    @if ($emojis)
                        <div class="mt-2">
                            <livewire:comment-reactions :presetEmojis="$emojis" :comment="$comment"/>
                        </div>
                    @endif
                </div>
                <div class="mt-6 text-sm flex items-center space-x-3">
                    @can('reply', $comment)
                        <button x-on:click="replying = true" class="text-gray-500">
                            Reply
                        </button>
                    @endcan
                    @can('edit', $comment)
                        <button x-on:click="editing = true" class="text-gray-500">
                            Edit
                        </button>
                    @endcan
                    @can('delete', $comment)
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                        class="text-gray-500">
                                    Delete
                                </button>

                                <!-- Popover content -->
                                <div x-show="open" @click.away="open = false" x-transition
                                     class="absolute mt-2 w-48 bg-white rounded shadow-lg py-2">
                                    <div class="px-4 py-2">
                                        <p>Are you sure you want to delete your comment?</p>
                                        <button
                                            type="button"
                                            wire:click="delete"
                                            class="inline-flex mt-2 text-white items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>

                    @endcan
                </div>
                <template x-if="replying">
                    <form wire:submit="reply" class="mt-4">
                        <div class="mb-4">
                            <x-markdown-editor :options="['b', 'i', 'h1', 'h2', 'ul', 'ol']" wire:model="replyForm.body"
                                               placeholder="Reply to {{ $comment->user->name ?? '[deleted user]' }}"
                                               class="w-full"
                                               rows="4"/>
                            @error('replyForm.body')
                                <p class="text-red-500 mt-1 mb-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                            Reply
                        </button>
                        <button class="ml-2 text-sm text-gray-500" x-on:click="replying = false">Cancel</button>
                    </form>
                </template>

                @if (is_null($comment->parent_id) && $comment->children->count())
                    <div class="ml-8 mt-8">
                        @foreach($comment->children as $child)
                            <div class="border-b border-gray-100 last:border-b-0" wire:key="{{ $child->id }}">
                                <livewire:comment-item :key="$child->id" :comment="$child"/>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
