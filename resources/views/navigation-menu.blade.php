<div>
<nav x-data="{ open: false, moreOpen: false, createOpen: false }" class="bg-white dark:bg-surface-900 border-b border-surface-200 dark:border-surface-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-14">
            <!-- Left: Logo -->
            <div class="flex items-center">
                <a wire:navigate href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-accent-500 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-xl">S</span>
                    </div>
                    <span class="hidden md:block font-bold text-xl text-surface-900 dark:text-white">
                        {{ config('app.name', 'Social') }}
                    </span>
                </a>
            </div>

            <!-- Center: Main Navigation (Desktop) -->
            <div class="hidden md:flex items-center justify-center flex-1 max-w-xl mx-8">
                <div class="flex items-center gap-1">
                    <a wire:navigate href="{{ route('dashboard') }}"
                       class="relative flex items-center justify-center w-24 h-12 rounded-xl transition-colors {{ request()->routeIs('dashboard') ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800' }}">
                        @if(request()->routeIs('dashboard'))
                            <x-heroicon-s-home class="w-6 h-6" />
                            <span class="absolute -bottom-[13px] left-1/2 -translate-x-1/2 w-12 h-1 bg-primary-500 rounded-t-full"></span>
                        @else
                            <x-heroicon-o-home class="w-6 h-6" />
                        @endif
                    </a>

                    <a wire:navigate href="{{ route('friends.index') }}"
                       class="relative flex items-center justify-center w-24 h-12 rounded-xl transition-colors {{ request()->routeIs('friends.*') ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800' }}">
                        @if(request()->routeIs('friends.*'))
                            <x-heroicon-s-users class="w-6 h-6" />
                            <span class="absolute -bottom-[13px] left-1/2 -translate-x-1/2 w-12 h-1 bg-primary-500 rounded-t-full"></span>
                        @else
                            <x-heroicon-o-users class="w-6 h-6" />
                        @endif
                        @if(auth()->user()->pendingFriendRequestsCount() > 0)
                            <span class="absolute top-1 right-4 min-w-[18px] h-[18px] flex items-center justify-center text-[10px] font-bold text-white bg-red-500 rounded-full px-1">
                                {{ auth()->user()->pendingFriendRequestsCount() > 9 ? '9+' : auth()->user()->pendingFriendRequestsCount() }}
                            </span>
                        @endif
                    </a>

                    <a wire:navigate href="{{ route('groups.index') }}"
                       class="relative flex items-center justify-center w-24 h-12 rounded-xl transition-colors {{ request()->routeIs('groups.*') ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800' }}">
                        @if(request()->routeIs('groups.*'))
                            <x-heroicon-s-user-group class="w-6 h-6" />
                            <span class="absolute -bottom-[13px] left-1/2 -translate-x-1/2 w-12 h-1 bg-primary-500 rounded-t-full"></span>
                        @else
                            <x-heroicon-o-user-group class="w-6 h-6" />
                        @endif
                    </a>

                    <a wire:navigate href="{{ route('marketplace.index') }}"
                       class="relative flex items-center justify-center w-24 h-12 rounded-xl transition-colors {{ request()->routeIs('marketplace.*') ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800' }}">
                        @if(request()->routeIs('marketplace.*'))
                            <x-heroicon-s-shopping-bag class="w-6 h-6" />
                            <span class="absolute -bottom-[13px] left-1/2 -translate-x-1/2 w-12 h-1 bg-primary-500 rounded-t-full"></span>
                        @else
                            <x-heroicon-o-shopping-bag class="w-6 h-6" />
                        @endif
                    </a>

                    <a wire:navigate href="{{ route('events.index') }}"
                       class="relative flex items-center justify-center w-24 h-12 rounded-xl transition-colors {{ request()->routeIs('events.*') ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800' }}">
                        @if(request()->routeIs('events.*'))
                            <x-heroicon-s-calendar-days class="w-6 h-6" />
                            <span class="absolute -bottom-[13px] left-1/2 -translate-x-1/2 w-12 h-1 bg-primary-500 rounded-t-full"></span>
                        @else
                            <x-heroicon-o-calendar-days class="w-6 h-6" />
                        @endif
                    </a>
                </div>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center gap-2">
                <!-- Search (Desktop) -->
                <div class="hidden lg:block w-56">
                    @livewire('search.global-search')
                </div>

                <!-- Create Button -->
                <div class="relative">
                    <button @click="createOpen = !createOpen"
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-surface-100 dark:bg-surface-800 text-surface-600 dark:text-surface-300 hover:bg-surface-200 dark:hover:bg-surface-700 transition-colors">
                        <x-heroicon-o-plus class="w-5 h-5" />
                    </button>
                    <div x-show="createOpen" @click.away="createOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-72 bg-white dark:bg-surface-800 rounded-xl shadow-xl border border-surface-200 dark:border-surface-700 py-2 z-50"
                         style="display: none;">
                        <div class="px-4 py-2 border-b border-surface-200 dark:border-surface-700">
                            <h3 class="font-semibold text-surface-900 dark:text-white">Create</h3>
                        </div>
                        <a wire:navigate href="{{ route('feed.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                            <div class="w-10 h-10 bg-surface-100 dark:bg-surface-700 rounded-full flex items-center justify-center">
                                <x-heroicon-o-pencil-square class="w-5 h-5 text-surface-600 dark:text-surface-300" />
                            </div>
                            <div>
                                <p class="font-medium text-surface-900 dark:text-white">Post</p>
                                <p class="text-xs text-surface-500">Share what's on your mind</p>
                            </div>
                        </a>
                        <a wire:navigate href="{{ route('groups.create') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                            <div class="w-10 h-10 bg-surface-100 dark:bg-surface-700 rounded-full flex items-center justify-center">
                                <x-heroicon-o-user-group class="w-5 h-5 text-surface-600 dark:text-surface-300" />
                            </div>
                            <div>
                                <p class="font-medium text-surface-900 dark:text-white">Group</p>
                                <p class="text-xs text-surface-500">Build your community</p>
                            </div>
                        </a>
                        <a wire:navigate href="{{ route('events.create') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                            <div class="w-10 h-10 bg-surface-100 dark:bg-surface-700 rounded-full flex items-center justify-center">
                                <x-heroicon-o-calendar class="w-5 h-5 text-surface-600 dark:text-surface-300" />
                            </div>
                            <div>
                                <p class="font-medium text-surface-900 dark:text-white">Event</p>
                                <p class="text-xs text-surface-500">Bring people together</p>
                            </div>
                        </a>
                        <a wire:navigate href="{{ route('marketplace.create') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                            <div class="w-10 h-10 bg-surface-100 dark:bg-surface-700 rounded-full flex items-center justify-center">
                                <x-heroicon-o-tag class="w-5 h-5 text-surface-600 dark:text-surface-300" />
                            </div>
                            <div>
                                <p class="font-medium text-surface-900 dark:text-white">Listing</p>
                                <p class="text-xs text-surface-500">Sell something</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Messages -->
                <a wire:navigate href="{{ route('messages.index') }}"
                   class="relative flex items-center justify-center w-10 h-10 rounded-full bg-surface-100 dark:bg-surface-800 text-surface-600 dark:text-surface-300 hover:bg-surface-200 dark:hover:bg-surface-700 transition-colors">
                    <x-heroicon-o-chat-bubble-left-right class="w-5 h-5" />
                </a>

                <!-- Notifications -->
                @livewire('notifications.notification-dropdown')

                <!-- Menu Button -->
                <div class="relative">
                    <button @click="moreOpen = !moreOpen"
                            class="flex items-center gap-2 p-1 rounded-full hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors">
                        <img class="w-9 h-9 rounded-full object-cover ring-2 ring-transparent hover:ring-primary-500/30 transition-all"
                             src="{{ Auth::user()->profile_photo_url }}"
                             alt="{{ Auth::user()->name }}" />
                    </button>

                    <!-- Menu Dropdown -->
                    <div x-show="moreOpen" @click.away="moreOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-80 bg-white dark:bg-surface-800 rounded-xl shadow-xl border border-surface-200 dark:border-surface-700 py-2 z-50"
                         style="display: none;">

                        <!-- User Info -->
                        <a wire:navigate href="{{ route('profile.view', Auth::user()) }}"
                           class="flex items-center gap-3 px-4 py-3 mx-2 rounded-lg hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                            <img class="w-12 h-12 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            <div>
                                <p class="font-semibold text-surface-900 dark:text-white">{{ Auth::user()->name }}</p>
                                <p class="text-sm text-surface-500">See your profile</p>
                            </div>
                        </a>

                        <div class="border-t border-surface-200 dark:border-surface-700 my-2"></div>

                        <!-- Quick Links -->
                        <div class="px-2 space-y-1">
                            <a wire:navigate href="{{ route('feed.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                                <div class="w-9 h-9 bg-surface-100 dark:bg-surface-700 rounded-full flex items-center justify-center">
                                    <x-heroicon-o-newspaper class="w-5 h-5 text-surface-600 dark:text-surface-300" />
                                </div>
                                <span class="font-medium text-surface-900 dark:text-white">Feed</span>
                            </a>
                            <a wire:navigate href="{{ route('media.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                                <div class="w-9 h-9 bg-surface-100 dark:bg-surface-700 rounded-full flex items-center justify-center">
                                    <x-heroicon-o-photo class="w-5 h-5 text-surface-600 dark:text-surface-300" />
                                </div>
                                <span class="font-medium text-surface-900 dark:text-white">Photos</span>
                            </a>
                            <a wire:navigate href="{{ route('watch.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                                <div class="w-9 h-9 bg-surface-100 dark:bg-surface-700 rounded-full flex items-center justify-center">
                                    <x-heroicon-o-play-circle class="w-5 h-5 text-surface-600 dark:text-surface-300" />
                                </div>
                                <span class="font-medium text-surface-900 dark:text-white">Watch</span>
                            </a>
                            <a wire:navigate href="{{ route('fundraisers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                                <div class="w-9 h-9 bg-surface-100 dark:bg-surface-700 rounded-full flex items-center justify-center">
                                    <x-heroicon-o-heart class="w-5 h-5 text-surface-600 dark:text-surface-300" />
                                </div>
                                <span class="font-medium text-surface-900 dark:text-white">Fundraisers</span>
                            </a>
                        </div>

                        <div class="border-t border-surface-200 dark:border-surface-700 my-2"></div>

                        <!-- Settings -->
                        <div class="px-2 space-y-1">
                            <a wire:navigate href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                                <div class="w-9 h-9 bg-surface-100 dark:bg-surface-700 rounded-full flex items-center justify-center">
                                    <x-heroicon-o-cog-6-tooth class="w-5 h-5 text-surface-600 dark:text-surface-300" />
                                </div>
                                <span class="font-medium text-surface-900 dark:text-white">Settings</span>
                            </a>

                            <!-- Dark Mode Toggle -->
                            <button x-data="{ dark: document.documentElement.classList.contains('dark') }"
                                    @click="dark = !dark; if(dark) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); } else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }"
                                    class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-surface-100 dark:bg-surface-700 rounded-full flex items-center justify-center">
                                        <x-heroicon-o-moon x-show="!dark" class="w-5 h-5 text-surface-600 dark:text-surface-300" />
                                        <x-heroicon-o-sun x-show="dark" x-cloak class="w-5 h-5 text-surface-600 dark:text-surface-300" />
                                    </div>
                                    <span class="font-medium text-surface-900 dark:text-white" x-text="dark ? 'Light Mode' : 'Dark Mode'"></span>
                                </div>
                                <div class="relative w-11 h-6 bg-surface-200 dark:bg-surface-600 rounded-full transition-colors"
                                     :class="dark ? 'bg-primary-500' : ''">
                                    <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow transition-transform"
                                         :class="dark ? 'translate-x-5' : ''"></div>
                                </div>
                            </button>
                        </div>

                        <div class="border-t border-surface-200 dark:border-surface-700 my-2"></div>

                        <!-- Logout -->
                        <div class="px-2">
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 transition-colors">
                                    <div class="w-9 h-9 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                        <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
                                    </div>
                                    <span class="font-medium">Log Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-surface-900 border-t border-surface-200 dark:border-surface-800 z-50 safe-area-pb">
    <div class="flex items-center justify-around h-16">
        <a wire:navigate href="{{ route('dashboard') }}"
           class="flex flex-col items-center justify-center flex-1 h-full {{ request()->routeIs('dashboard') ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500' }}">
            @if(request()->routeIs('dashboard'))
                <x-heroicon-s-home class="w-6 h-6" />
            @else
                <x-heroicon-o-home class="w-6 h-6" />
            @endif
            <span class="text-[10px] mt-0.5">Home</span>
        </a>

        <a wire:navigate href="{{ route('friends.index') }}"
           class="relative flex flex-col items-center justify-center flex-1 h-full {{ request()->routeIs('friends.*') ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500' }}">
            @if(request()->routeIs('friends.*'))
                <x-heroicon-s-users class="w-6 h-6" />
            @else
                <x-heroicon-o-users class="w-6 h-6" />
            @endif
            <span class="text-[10px] mt-0.5">Friends</span>
            @if(auth()->user()->pendingFriendRequestsCount() > 0)
                <span class="absolute top-2 right-1/4 min-w-[16px] h-4 flex items-center justify-center text-[10px] font-bold text-white bg-red-500 rounded-full px-1">
                    {{ auth()->user()->pendingFriendRequestsCount() > 9 ? '9+' : auth()->user()->pendingFriendRequestsCount() }}
                </span>
            @endif
        </a>

        <a wire:navigate href="{{ route('feed.index') }}"
           class="flex flex-col items-center justify-center flex-1 h-full {{ request()->routeIs('feed.*') ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500' }}">
            @if(request()->routeIs('feed.*'))
                <x-heroicon-s-plus-circle class="w-7 h-7" />
            @else
                <x-heroicon-o-plus-circle class="w-7 h-7" />
            @endif
            <span class="text-[10px] mt-0.5">Post</span>
        </a>

        <a wire:navigate href="{{ route('messages.index') }}"
           class="flex flex-col items-center justify-center flex-1 h-full {{ request()->routeIs('messages.*') ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500' }}">
            @if(request()->routeIs('messages.*'))
                <x-heroicon-s-chat-bubble-left-right class="w-6 h-6" />
            @else
                <x-heroicon-o-chat-bubble-left-right class="w-6 h-6" />
            @endif
            <span class="text-[10px] mt-0.5">Chat</span>
        </a>

        <a wire:navigate href="{{ route('notifications.index') }}"
           class="flex flex-col items-center justify-center flex-1 h-full {{ request()->routeIs('notifications.*') ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500' }}">
            @if(request()->routeIs('notifications.*'))
                <x-heroicon-s-bell class="w-6 h-6" />
            @else
                <x-heroicon-o-bell class="w-6 h-6" />
            @endif
            <span class="text-[10px] mt-0.5">Alerts</span>
        </a>
    </div>
</nav>
</div>
