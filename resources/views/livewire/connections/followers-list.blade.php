<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <!-- Header with Tabs -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    @if($user && $user->id !== auth()->id())
                        {{ $user->profile?->display_name ?? $user->name }}
                    @else
                        My
                    @endif
                </h2>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $users->count() }} {{ $tab === 'followers' ? Str::plural('follower', $users->count()) : 'following' }}
                </span>
            </div>

            <!-- Tabs -->
            <div class="flex space-x-4 border-b border-gray-200 dark:border-gray-700 -mb-4">
                <button wire:click="setTab('followers')"
                        class="pb-4 px-1 text-sm font-medium {{ $tab === 'followers' ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                    Followers
                </button>
                <button wire:click="setTab('following')"
                        class="pb-4 px-1 text-sm font-medium {{ $tab === 'following' ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                    Following
                </button>
            </div>
        </div>

        <!-- Search -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search..."
                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <!-- Users List -->
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($users as $listUser)
                <div class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <a href="{{ route('profile.view', $listUser) }}" class="flex items-center space-x-4">
                        <img class="h-12 w-12 rounded-full object-cover"
                             src="{{ $listUser->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($listUser->name) }}"
                             alt="{{ $listUser->name }}">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $listUser->profile?->display_name ?? $listUser->name }}
                            </p>
                            @if($listUser->profile?->username)
                                <p class="text-sm text-gray-500 dark:text-gray-400">@{{ $listUser->profile->username }}</p>
                            @endif
                        </div>
                    </a>
                    @if(auth()->id() !== $listUser->id)
                        <div class="flex space-x-2">
                            @livewire('connections.follow-button', ['user' => $listUser], key('follow-'.$listUser->id))
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
                        No {{ $tab === 'followers' ? 'followers' : 'following' }} yet
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @if($tab === 'followers')
                            {{ $user && $user->id === auth()->id() ? 'You don\'t have any followers yet.' : 'This user doesn\'t have any followers yet.' }}
                        @else
                            {{ $user && $user->id === auth()->id() ? 'You aren\'t following anyone yet.' : 'This user isn\'t following anyone yet.' }}
                        @endif
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>
