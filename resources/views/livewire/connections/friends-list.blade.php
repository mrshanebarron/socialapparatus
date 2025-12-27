<div>
    <x-ui.card>
        <!-- Header -->
        <div class="pb-4 border-b border-surface-200 dark:border-surface-700">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-surface-900 dark:text-white">
                    @if($isOwn)
                        My Friends
                    @else
                        {{ $displayName }}'s Friends
                    @endif
                </h2>
                <span class="text-sm text-surface-500 dark:text-surface-400">
                    {{ $friends->count() }} {{ Str::plural('friend', $friends->count()) }}
                </span>
            </div>
            <div class="mt-4">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search friends..."
                       class="w-full rounded-xl border-surface-300 dark:border-surface-600 dark:bg-surface-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
        </div>

        <!-- Friends List -->
        <div class="divide-y divide-surface-200 dark:divide-surface-700 -mx-5">
            @forelse($friends as $friend)
                <div class="p-4 flex items-center justify-between hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors">
                    <a wire:navigate href="{{ route('profile.view', $friend) }}" class="flex items-center space-x-4">
                        <img class="h-12 w-12 rounded-xl object-cover"
                             src="{{ $friend->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($friend->name) }}"
                             alt="{{ $friend->name }}">
                        <div>
                            <p class="text-sm font-medium text-surface-900 dark:text-white">
                                {{ $friend->profile?->display_name ?? $friend->name }}
                            </p>
                            @if($friend->profile?->username)
                                <p class="text-sm text-surface-500 dark:text-surface-400">@{{ $friend->profile->username }}</p>
                            @endif
                        </div>
                    </a>
                    @if(auth()->id() !== $friend->id)
                        <div class="flex space-x-2">
                            @livewire('connections.connection-button', ['user' => $friend], key('conn-'.$friend->id))
                            @livewire('connections.follow-button', ['user' => $friend], key('follow-'.$friend->id))
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-8 text-center">
                    <x-heroicon-o-users class="mx-auto h-12 w-12 text-surface-400" />
                    <h3 class="mt-2 text-sm font-medium text-surface-900 dark:text-white">No friends yet</h3>
                    <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">
                        @if($isOwn)
                            Start connecting with people to build your friends list.
                        @else
                            This user hasn't added any friends yet.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>
    </x-ui.card>
</div>
