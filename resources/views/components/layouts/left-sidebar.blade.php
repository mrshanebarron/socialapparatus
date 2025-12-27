@auth
<div class="lg:col-span-3 space-y-6">
    <!-- User Profile Card -->
    <x-ui.card>
        <div class="text-center">
            <div class="relative inline-block mb-4">
                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-primary-100 dark:ring-primary-900/30">
                <span class="absolute bottom-1 right-1 w-4 h-4 bg-green-500 rounded-full ring-2 ring-white dark:ring-surface-800"></span>
            </div>
            <h3 class="text-lg font-semibold text-surface-900 dark:text-surface-100">{{ auth()->user()->name }}</h3>
            <p class="text-sm text-surface-500 mb-4">{{ auth()->user()->profile?->username ? '@' . auth()->user()->profile->username : '@' . Str::slug(auth()->user()->name) }}</p>

            <div class="grid grid-cols-3 gap-4 py-4 border-t border-surface-200 dark:border-surface-700">
                <div>
                    <p class="text-lg font-bold text-surface-900 dark:text-surface-100">{{ auth()->user()->posts()->count() }}</p>
                    <p class="text-xs text-surface-500">Posts</p>
                </div>
                <div>
                    <p class="text-lg font-bold text-surface-900 dark:text-surface-100">{{ auth()->user()->friends()->count() }}</p>
                    <p class="text-xs text-surface-500">Friends</p>
                </div>
                <div>
                    <p class="text-lg font-bold text-surface-900 dark:text-surface-100">{{ auth()->user()->groups()->count() }}</p>
                    <p class="text-xs text-surface-500">Groups</p>
                </div>
            </div>
        </div>

        <a wire:navigate href="{{ route('profile.view', ['user' => auth()->id()]) }}" class="block w-full text-center py-2.5 text-sm font-medium text-primary-600 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-xl transition-colors">
            View Profile
        </a>
    </x-ui.card>

    <!-- Quick Links -->
    <x-ui.card>
        <h4 class="font-semibold text-surface-900 dark:text-surface-100 mb-4">Quick Links</h4>
        <nav class="space-y-1">
            <a wire:navigate href="{{ route('feed.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('feed.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700' }} transition-colors">
                <x-heroicon-o-newspaper class="w-5 h-5 {{ request()->routeIs('feed.*') ? 'text-primary-500' : 'text-primary-500' }}" />
                <span>News Feed</span>
            </a>
            <a wire:navigate href="{{ route('messages.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('messages.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700' }} transition-colors">
                <x-heroicon-o-chat-bubble-left-right class="w-5 h-5 text-accent-500" />
                <span>Messages</span>
            </a>
            <a wire:navigate href="{{ route('friends.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('friends.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700' }} transition-colors">
                <x-heroicon-o-users class="w-5 h-5 text-green-500" />
                <span>Friends</span>
            </a>
            <a wire:navigate href="{{ route('groups.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('groups.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700' }} transition-colors">
                <x-heroicon-o-user-group class="w-5 h-5 text-purple-500" />
                <span>Groups</span>
            </a>
            <a wire:navigate href="{{ route('events.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('events.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700' }} transition-colors">
                <x-heroicon-o-calendar-days class="w-5 h-5 text-orange-500" />
                <span>Events</span>
            </a>
            <a wire:navigate href="{{ route('marketplace.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('marketplace.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700' }} transition-colors">
                <x-heroicon-o-shopping-bag class="w-5 h-5 text-pink-500" />
                <span>Marketplace</span>
            </a>
            <a wire:navigate href="{{ route('media.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('media.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700' }} transition-colors">
                <x-heroicon-o-photo class="w-5 h-5 text-cyan-500" />
                <span>Photos</span>
            </a>
        </nav>
    </x-ui.card>

    <!-- Your Groups -->
    @php $userGroups = auth()->user()->groups(); @endphp
    @if($userGroups->count() > 0)
        <x-ui.card>
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-semibold text-surface-900 dark:text-surface-100">Your Groups</h4>
                <a wire:navigate href="{{ route('groups.index') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">See all</a>
            </div>
            <div class="space-y-3">
                @foreach($userGroups->take(5) as $group)
                    <a wire:navigate href="{{ route('groups.show', $group) }}" class="flex items-center gap-3 group">
                        @if($group->avatar)
                            <img src="{{ $group->avatar }}" alt="{{ $group->name }}" class="w-10 h-10 rounded-xl object-cover">
                        @else
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center">
                                <span class="text-white font-semibold text-sm">{{ substr($group->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-surface-900 dark:text-surface-100 truncate group-hover:text-primary-600 dark:group-hover:text-primary-400">{{ $group->name }}</p>
                            <p class="text-xs text-surface-500">{{ $group->members_count ?? 0 }} members</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </x-ui.card>
    @endif
</div>
@endauth
