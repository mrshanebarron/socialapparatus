<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Suggestions for You</h3>

    <!-- Type Tabs -->
    <div class="flex space-x-2 mb-4 overflow-x-auto">
        <button wire:click="setType('people_you_may_know')" class="px-3 py-1 text-xs rounded-full whitespace-nowrap {{ $type === 'people_you_may_know' ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200' }}">
            People
        </button>
        <button wire:click="setType('suggested_groups')" class="px-3 py-1 text-xs rounded-full whitespace-nowrap {{ $type === 'suggested_groups' ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200' }}">
            Groups
        </button>
        <button wire:click="setType('suggested_pages')" class="px-3 py-1 text-xs rounded-full whitespace-nowrap {{ $type === 'suggested_pages' ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200' }}">
            Pages
        </button>
    </div>

    @if($recommendations->isEmpty())
        <p class="text-sm text-gray-500 dark:text-gray-400">No suggestions available.</p>
    @else
        <div class="space-y-3">
            @foreach($recommendations as $recommendation)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 min-w-0">
                        @if($recommendation->recommendable)
                            @if($type === 'people_you_may_know')
                                <img class="h-10 w-10 rounded-full object-cover flex-shrink-0" src="{{ $recommendation->recommendable->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($recommendation->recommendable->name) }}" alt="">
                                <div class="min-w-0">
                                    <a href="{{ route('profile.view', $recommendation->recommendable) }}" class="font-medium text-gray-900 dark:text-white text-sm hover:underline truncate block">
                                        {{ $recommendation->recommendable->profile?->display_name ?? $recommendation->recommendable->name }}
                                    </a>
                                    @if($recommendation->reason)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $recommendation->reason }}</p>
                                    @endif
                                </div>
                            @elseif($type === 'suggested_groups')
                                <img class="h-10 w-10 rounded-lg object-cover flex-shrink-0" src="{{ $recommendation->recommendable->cover_photo ? Storage::url($recommendation->recommendable->cover_photo) : 'https://ui-avatars.com/api/?name='.urlencode($recommendation->recommendable->name) }}" alt="">
                                <div class="min-w-0">
                                    <a href="{{ route('groups.show', $recommendation->recommendable) }}" class="font-medium text-gray-900 dark:text-white text-sm hover:underline truncate block">
                                        {{ $recommendation->recommendable->name }}
                                    </a>
                                    @if($recommendation->reason)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $recommendation->reason }}</p>
                                    @endif
                                </div>
                            @elseif($type === 'suggested_pages')
                                <img class="h-10 w-10 rounded-lg object-cover flex-shrink-0" src="{{ $recommendation->recommendable->avatar ? Storage::url($recommendation->recommendable->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($recommendation->recommendable->name) }}" alt="">
                                <div class="min-w-0">
                                    <a href="{{ route('pages.show', $recommendation->recommendable) }}" class="font-medium text-gray-900 dark:text-white text-sm hover:underline truncate block">
                                        {{ $recommendation->recommendable->name }}
                                    </a>
                                    @if($recommendation->reason)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $recommendation->reason }}</p>
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>
                    <button wire:click="dismiss({{ $recommendation->id }})" class="text-gray-400 hover:text-gray-600 flex-shrink-0" title="Dismiss">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>
