<div class="flex items-center space-x-2 mb-2">
    <div class="flex space-x-2">
        @foreach ($displayedEmojis as $emoji)
            <button
                wire:click="toggleReaction('{{ $emoji }}')"
                class="flex items-center space-x-1 bg-gray-100 p-1 rounded {{ $this->hasUserReacted($emoji) ? 'bg-blue-200' : '' }}"
            >
                <span class="transition-transform transform hover:scale-110">{{ $emoji }}</span>
                <span class="text-xs">{{ $this->getReactionCount($emoji) }}</span>
            </button>
        @endforeach
    </div>

    @auth
        <div x-data="{ showEmojiPicker: false }" class="relative {{ $displayedEmojis ? '' :'!ml-0' }}">
            <button @click="showEmojiPicker = !showEmojiPicker" class="bg-blue-500 text-white px-2.5 py-1 rounded hover:bg-blue-600 focus:outline-none">
                +
            </button>
            <div x-show="showEmojiPicker" @click.away="showEmojiPicker = false"
                 class="absolute left-0 top-full mt-2 bg-white border border-gray-300 rounded shadow-lg p-3 flex space-x-2 z-10 transition-opacity duration-200"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-90"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-90">
                @foreach ($presetEmojis as $emoji)
                    <button wire:click="toggleReaction('{{ $emoji }}')" @click="showEmojiPicker = false"
                            class="text-lg hover:bg-gray-100 p-1 rounded transition-transform transform hover:scale-125">
                        {{ $emoji }}
                    </button>
                @endforeach
            </div>
        </div>
    @endauth
</div>
