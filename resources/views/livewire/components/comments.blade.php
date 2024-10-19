<div>
    @if (count($chunks))
        <div class="mt-8 px-6">
            @for($chunk = 0; $chunk < $page; $chunk++)
                <div class="border-b border-gray-100 dark:border-gray-900 last:border-b-0"
                     wire:key="chunks-{{ $chunk }}">
                    <livewire:comment-chunk :markdownOptions="$markdownOptions"
                                            :allowGuests="$allowGuests"
                                            :maxDepth="$maxDepth"
                                            :emojis="$emojis"
                                            :ids="$chunks[$chunk]"
                                            wire:key="chunk-{{ md5(json_encode($this->chunks[$chunk])) }}"/>
                </div>
            @endfor
        </div>
    @endif


    @if ($this->hasMorePages())
        <div class="mt-8 flex items-center justify-center">
            <button
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                wire:click="loadMore">
                Load more
            </button>
        </div>
    @endif
    @if ($this->allowGuests || auth()->user())
        <form wire:submit="createComment" class="mt-4">
            <div class="mb-3">
                <x-markdown-editor :options="$markdownOptions" wire:model="form.body" placeholder="Post a comment"
                                   class="w-full" rows="4"/>

                @error('form.body')
                <p class="text-red-500 dark:text-red-400 mt-1 mb-1">{{ $message }}</p>
                @enderror
            </div>
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-900 border border-transparent rounded-md font-semibold text-xs dark:text-gray-100 text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                Post a Comment
            </button>
        </form>
    @endif
</div>
