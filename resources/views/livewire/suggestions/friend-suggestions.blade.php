<div>
    @if($suggestions->isEmpty())
        <p class="text-sm text-surface-500 dark:text-surface-400">No suggestions at this time. Add more friends to discover mutual connections!</p>
    @else
        <div class="space-y-3">
            @foreach($suggestions as $user)
                <div class="flex items-center justify-between">
                    <a wire:navigate href="{{ route('profile.view', $user->profile?->username ?? $user->id) }}" class="flex items-center space-x-3 flex-1 min-w-0">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-xl flex-shrink-0 object-cover">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-surface-900 dark:text-white text-sm truncate">{{ $user->name }}</p>
                            @if($user->mutual_friends_count > 0)
                                <p class="text-xs text-surface-500 dark:text-surface-400">{{ $user->mutual_friends_count }} mutual {{ Str::plural('friend', $user->mutual_friends_count) }}</p>
                            @elseif($user->profile?->headline)
                                <p class="text-xs text-surface-500 dark:text-surface-400 truncate">{{ $user->profile->headline }}</p>
                            @endif
                        </div>
                    </a>
                    <button wire:click="sendRequest({{ $user->id }})"
                            class="ml-2 px-3 py-1.5 text-xs font-medium text-primary-600 bg-primary-100 hover:bg-primary-200 dark:text-primary-400 dark:bg-primary-900/50 dark:hover:bg-primary-900 rounded-lg transition-colors">
                        Add
                    </button>
                </div>
            @endforeach
        </div>

        <a wire:navigate href="{{ route('friends.index') }}" class="block mt-4 text-center text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
            See All Suggestions
        </a>
    @endif
</div>
