<div class="max-w-4xl mx-auto">
    @if(!$canView)
        <x-ui.card>
            <x-ui.empty-state
                icon="lock-closed"
                title="This profile is private"
                description="You don't have permission to view this profile."
            />
        </x-ui.card>
    @else
        <!-- Cover Photo -->
        <div class="relative h-48 md:h-72 rounded-2xl overflow-hidden">
            @if($profile->cover_photo_url)
                <img src="{{ $profile->cover_photo_url }}" alt="Cover" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-primary-500 via-accent-500 to-primary-600"></div>
            @endif
            <!-- Gradient overlay for better text contrast -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>

            @if($isOwner)
                <button class="absolute bottom-4 right-4 px-4 py-2 bg-black/30 backdrop-blur-sm text-white rounded-xl text-sm font-medium hover:bg-black/40 transition-colors flex items-center gap-2">
                    <x-heroicon-o-camera class="w-4 h-4" />
                    Edit Cover
                </button>
            @endif
        </div>

        <!-- Profile Header Card -->
        <div class="relative -mt-16 mx-4">
            <x-ui.card class="overflow-visible">
                <div class="px-4 sm:px-6 pb-6">
                    <div class="sm:flex sm:items-end sm:gap-6 -mt-20">
                        <!-- Avatar -->
                        <div class="relative flex-shrink-0">
                            <div class="relative">
                                <img class="h-32 w-32 sm:h-36 sm:w-36 rounded-2xl ring-4 ring-white dark:ring-surface-800 object-cover shadow-soft-lg"
                                     src="{{ $profile->avatar_url }}"
                                     alt="{{ $profile->display_name_or_username }}">
                                @if($profile->show_online_status && $profile->is_online)
                                    <span class="absolute bottom-2 right-2 block h-5 w-5 rounded-full bg-green-500 ring-4 ring-white dark:ring-surface-800"></span>
                                @endif
                            </div>
                            @if($isOwner)
                                <button class="absolute bottom-2 right-2 p-2 bg-primary-500 text-white rounded-xl shadow-md hover:bg-primary-600 transition-colors">
                                    <x-heroicon-o-camera class="w-4 h-4" />
                                </button>
                            @endif
                        </div>

                        <!-- Name and Actions -->
                        <div class="mt-6 sm:mt-0 sm:flex-1 sm:min-w-0 sm:flex sm:items-end sm:justify-between">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-3">
                                    <h1 class="text-2xl font-bold text-surface-900 dark:text-white truncate">
                                        {{ $profile->display_name_or_username }}
                                    </h1>
                                    @if($profile->user->verified_at)
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-primary-500 rounded-full" title="Verified">
                                            <x-heroicon-s-check class="w-4 h-4 text-white" />
                                        </span>
                                    @endif
                                </div>
                                @if($profile->username)
                                    <p class="text-surface-500 dark:text-surface-400">{{ '@' . $profile->username }}</p>
                                @endif
                                @if($profile->bio)
                                    <p class="mt-2 text-surface-600 dark:text-surface-300 max-w-2xl">{{ $profile->bio }}</p>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
                                @if($isOwner)
                                    <x-ui.button href="{{ route('profile.edit') }}" variant="secondary" size="md">
                                        <x-heroicon-o-pencil class="w-4 h-4 mr-2" />
                                        Edit Profile
                                    </x-ui.button>
                                @else
                                    @if($profile->allow_friend_requests)
                                        @livewire('connections.connection-button', ['user' => $profile->user])
                                    @endif
                                    @livewire('connections.follow-button', ['user' => $profile->user])
                                    @if($profile->allow_messages)
                                        <x-ui.button href="{{ route('messages.create', ['user' => $profile->user->id]) }}" variant="secondary" size="md">
                                            <x-heroicon-o-chat-bubble-left-right class="w-4 h-4 mr-2" />
                                            Message
                                        </x-ui.button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Bar -->
                <div class="border-t border-surface-200 dark:border-surface-700">
                    <div class="grid grid-cols-3 divide-x divide-surface-200 dark:divide-surface-700">
                        <a href="{{ route('profile.friends', $profile->user) }}" class="py-4 text-center hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors group">
                            <p class="text-2xl font-bold text-surface-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ number_format($profile->friends_count) }}</p>
                            <p class="text-sm text-surface-500 dark:text-surface-400">Friends</p>
                        </a>
                        <a href="{{ route('profile.followers', $profile->user) }}" class="py-4 text-center hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors group">
                            <p class="text-2xl font-bold text-surface-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ number_format($profile->followers_count) }}</p>
                            <p class="text-sm text-surface-500 dark:text-surface-400">Followers</p>
                        </a>
                        <div class="py-4 text-center">
                            <p class="text-2xl font-bold text-surface-900 dark:text-white">{{ number_format($profile->posts_count) }}</p>
                            <p class="text-sm text-surface-500 dark:text-surface-400">Posts</p>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Profile Tabs -->
        <div class="mt-6 mx-4">
            <x-ui.card padding="none">
                <nav class="flex overflow-x-auto" aria-label="Profile tabs">
                    <button wire:click="setTab('posts')" class="flex-1 py-4 px-4 text-center font-medium text-sm transition-all duration-200 relative whitespace-nowrap {{ $activeTab === 'posts' ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-300' }}">
                        <span class="flex items-center justify-center gap-2">
                            <x-heroicon-o-document-text class="w-4 h-4" />
                            Posts
                        </span>
                        @if($activeTab === 'posts')
                            <span class="absolute bottom-0 inset-x-4 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full"></span>
                        @endif
                    </button>
                    <button wire:click="setTab('about')" class="flex-1 py-4 px-4 text-center font-medium text-sm transition-all duration-200 relative whitespace-nowrap {{ $activeTab === 'about' ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-300' }}">
                        <span class="flex items-center justify-center gap-2">
                            <x-heroicon-o-information-circle class="w-4 h-4" />
                            About
                        </span>
                        @if($activeTab === 'about')
                            <span class="absolute bottom-0 inset-x-4 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full"></span>
                        @endif
                    </button>
                    <button wire:click="setTab('photos')" class="flex-1 py-4 px-4 text-center font-medium text-sm transition-all duration-200 relative whitespace-nowrap {{ $activeTab === 'photos' ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-300' }}">
                        <span class="flex items-center justify-center gap-2">
                            <x-heroicon-o-photo class="w-4 h-4" />
                            Photos
                        </span>
                        @if($activeTab === 'photos')
                            <span class="absolute bottom-0 inset-x-4 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full"></span>
                        @endif
                    </button>
                    <button wire:click="setTab('friends')" class="flex-1 py-4 px-4 text-center font-medium text-sm transition-all duration-200 relative whitespace-nowrap {{ $activeTab === 'friends' ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-300' }}">
                        <span class="flex items-center justify-center gap-2">
                            <x-heroicon-o-users class="w-4 h-4" />
                            Friends
                        </span>
                        @if($activeTab === 'friends')
                            <span class="absolute bottom-0 inset-x-4 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full"></span>
                        @endif
                    </button>
                </nav>
            </x-ui.card>
        </div>

        <!-- Tab Content -->
        <div class="mt-6 mx-4 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sidebar (About section - always visible on desktop) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Intro Card -->
                <x-ui.card>
                    <h3 class="text-lg font-bold text-surface-900 dark:text-white mb-4">Intro</h3>

                    <div class="space-y-4">
                        @if($profile->bio)
                            <p class="text-surface-600 dark:text-surface-300 text-center">{{ $profile->bio }}</p>
                        @endif

                        @if($profile->location)
                            <div class="flex items-center gap-3 text-sm">
                                <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-surface-100 dark:bg-surface-700 flex items-center justify-center">
                                    <x-heroicon-o-map-pin class="w-4 h-4 text-surface-500" />
                                </div>
                                <span class="text-surface-700 dark:text-surface-300">Lives in <span class="font-semibold">{{ $profile->location }}</span></span>
                            </div>
                        @endif

                        @if($profile->website)
                            <div class="flex items-center gap-3 text-sm">
                                <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-surface-100 dark:bg-surface-700 flex items-center justify-center">
                                    <x-heroicon-o-link class="w-4 h-4 text-surface-500" />
                                </div>
                                <a href="{{ $profile->website }}" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline truncate">{{ str_replace(['https://', 'http://'], '', $profile->website) }}</a>
                            </div>
                        @endif

                        @if($profile->birthday && ($isOwner || $profile->profile_visibility === 'public'))
                            <div class="flex items-center gap-3 text-sm">
                                <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-surface-100 dark:bg-surface-700 flex items-center justify-center">
                                    <x-heroicon-o-cake class="w-4 h-4 text-surface-500" />
                                </div>
                                <span class="text-surface-700 dark:text-surface-300">Born on <span class="font-semibold">{{ $profile->birthday->format('F j') }}</span></span>
                            </div>
                        @endif

                        <div class="flex items-center gap-3 text-sm">
                            <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-surface-100 dark:bg-surface-700 flex items-center justify-center">
                                <x-heroicon-o-calendar class="w-4 h-4 text-surface-500" />
                            </div>
                            <span class="text-surface-700 dark:text-surface-300">Joined <span class="font-semibold">{{ $profile->created_at->format('F Y') }}</span></span>
                        </div>

                        @if($profile->show_last_seen && $profile->last_seen_at && !$profile->is_online)
                            <div class="flex items-center gap-3 text-sm">
                                <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-surface-100 dark:bg-surface-700 flex items-center justify-center">
                                    <x-heroicon-o-clock class="w-4 h-4 text-surface-500" />
                                </div>
                                <span class="text-surface-700 dark:text-surface-300">Last seen <span class="font-semibold">{{ $profile->last_seen_at->diffForHumans() }}</span></span>
                            </div>
                        @endif
                    </div>

                    @if($isOwner)
                        <x-ui.button href="{{ route('profile.edit') }}" variant="secondary" class="w-full mt-4">
                            Edit Details
                        </x-ui.button>
                    @endif
                </x-ui.card>

                <!-- Photos Preview Card -->
                <x-ui.card>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-surface-900 dark:text-white">Photos</h3>
                        <a href="#" wire:click.prevent="setTab('photos')" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">See all</a>
                    </div>
                    @if(isset($photos) && $photos->count() > 0)
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($photos->take(9) as $photo)
                                <a href="#" class="aspect-square rounded-xl overflow-hidden">
                                    <img src="{{ $photo->url }}" alt="" class="w-full h-full object-cover hover:opacity-90 transition-opacity">
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-surface-500 dark:text-surface-400 text-sm text-center py-4">No photos yet</p>
                    @endif
                </x-ui.card>

                <!-- Friends Preview Card -->
                <x-ui.card>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-surface-900 dark:text-white">Friends</h3>
                        <a href="{{ route('profile.friends', $profile->user) }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">See all</a>
                    </div>
                    <p class="text-surface-500 dark:text-surface-400 text-sm mb-4">{{ number_format($profile->friends_count) }} friends</p>
                    @if(isset($friends) && $friends->count() > 0)
                        <div class="grid grid-cols-3 gap-3">
                            @foreach($friends->take(9) as $friend)
                                <a href="{{ route('profile.view', $friend) }}" class="text-center group">
                                    <img src="{{ $friend->profile_photo_url }}" alt="{{ $friend->name }}" class="w-full aspect-square rounded-xl object-cover group-hover:opacity-90 transition-opacity">
                                    <p class="text-xs text-surface-700 dark:text-surface-300 mt-1 truncate group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $friend->name }}</p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-surface-500 dark:text-surface-400 text-sm text-center py-4">No friends yet</p>
                    @endif
                </x-ui.card>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                @if($activeTab === 'posts')
                    <!-- Create Post (only for owner) -->
                    @if($isOwner)
                        <x-ui.card>
                            <div class="flex items-start gap-4">
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-11 h-11 rounded-xl object-cover">
                                <a wire:navigate href="{{ route('feed.index') }}" class="flex-1 px-4 py-3 bg-surface-100 dark:bg-surface-700 rounded-xl text-surface-500 dark:text-surface-400 hover:bg-surface-200 dark:hover:bg-surface-600 transition-colors text-left">
                                    What's on your mind?
                                </a>
                            </div>
                        </x-ui.card>
                    @endif

                    <!-- Posts List -->
                    @if(isset($posts) && $posts->count() > 0)
                        @foreach($posts as $post)
                            <livewire:feed.post-card :post="$post" :key="$post->id" />
                        @endforeach
                    @else
                        <x-ui.card>
                            <x-ui.empty-state
                                icon="document-text"
                                title="No posts yet"
                                :description="$isOwner ? 'Share your first post with your friends!' : 'This user hasn\'t posted anything yet.'"
                                :action="$isOwner ? route('feed.index') : null"
                                :actionText="$isOwner ? 'Create Post' : null"
                            />
                        </x-ui.card>
                    @endif
                @elseif($activeTab === 'about')
                    <x-ui.card>
                        <h3 class="text-lg font-bold text-surface-900 dark:text-white mb-6">About {{ $profile->display_name_or_username }}</h3>

                        <!-- Overview Section -->
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-sm font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-4">Overview</h4>
                                <div class="space-y-4">
                                    @if($profile->bio)
                                        <div class="flex items-start gap-3">
                                            <x-heroicon-o-chat-bubble-bottom-center-text class="w-5 h-5 text-surface-400 mt-0.5" />
                                            <p class="text-surface-700 dark:text-surface-300">{{ $profile->bio }}</p>
                                        </div>
                                    @endif
                                    @if($profile->location)
                                        <div class="flex items-center gap-3">
                                            <x-heroicon-o-map-pin class="w-5 h-5 text-surface-400" />
                                            <span class="text-surface-700 dark:text-surface-300">Lives in <span class="font-semibold">{{ $profile->location }}</span></span>
                                        </div>
                                    @endif
                                    @if($profile->hometown)
                                        <div class="flex items-center gap-3">
                                            <x-heroicon-o-home class="w-5 h-5 text-surface-400" />
                                            <span class="text-surface-700 dark:text-surface-300">From <span class="font-semibold">{{ $profile->hometown }}</span></span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Contact Info Section -->
                            @if($profile->website || $profile->email)
                                <div class="border-t border-surface-200 dark:border-surface-700 pt-6">
                                    <h4 class="text-sm font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-4">Contact Info</h4>
                                    <div class="space-y-4">
                                        @if($profile->website)
                                            <div class="flex items-center gap-3">
                                                <x-heroicon-o-globe-alt class="w-5 h-5 text-surface-400" />
                                                <a href="{{ $profile->website }}" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline">{{ $profile->website }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Basic Info Section -->
                            <div class="border-t border-surface-200 dark:border-surface-700 pt-6">
                                <h4 class="text-sm font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-4">Basic Info</h4>
                                <div class="space-y-4">
                                    @if($profile->birthday && ($isOwner || $profile->profile_visibility === 'public'))
                                        <div class="flex items-center gap-3">
                                            <x-heroicon-o-cake class="w-5 h-5 text-surface-400" />
                                            <span class="text-surface-700 dark:text-surface-300">Birthday: <span class="font-semibold">{{ $profile->birthday->format('F j, Y') }}</span></span>
                                        </div>
                                    @endif
                                    @if($profile->gender)
                                        <div class="flex items-center gap-3">
                                            <x-heroicon-o-user class="w-5 h-5 text-surface-400" />
                                            <span class="text-surface-700 dark:text-surface-300">{{ ucfirst($profile->gender) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-3">
                                        <x-heroicon-o-calendar class="w-5 h-5 text-surface-400" />
                                        <span class="text-surface-700 dark:text-surface-300">Joined <span class="font-semibold">{{ $profile->created_at->format('F j, Y') }}</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-ui.card>
                @elseif($activeTab === 'photos')
                    <x-ui.card>
                        <h3 class="text-lg font-bold text-surface-900 dark:text-white mb-6">Photos</h3>
                        @if(isset($photos) && $photos->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($photos as $photo)
                                    <a href="#" class="aspect-square rounded-xl overflow-hidden">
                                        <img src="{{ $photo->url }}" alt="" class="w-full h-full object-cover hover:opacity-90 transition-opacity">
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <x-ui.empty-state
                                icon="photo"
                                title="No photos"
                                description="No photos have been uploaded yet."
                            />
                        @endif
                    </x-ui.card>
                @elseif($activeTab === 'friends')
                    <x-ui.card>
                        <h3 class="text-lg font-bold text-surface-900 dark:text-white mb-6">Friends ({{ number_format($profile->friends_count) }})</h3>
                        @if(isset($friends) && $friends->count() > 0)
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($friends as $friend)
                                    <a href="{{ route('profile.view', $friend) }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors group">
                                        <img src="{{ $friend->profile_photo_url }}" alt="{{ $friend->name }}" class="w-16 h-16 rounded-xl object-cover">
                                        <div>
                                            <p class="font-semibold text-surface-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $friend->name }}</p>
                                            @if($friend->profile?->location)
                                                <p class="text-sm text-surface-500 dark:text-surface-400">{{ $friend->profile->location }}</p>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <x-ui.empty-state
                                icon="users"
                                title="No friends yet"
                                :description="$isOwner ? 'Start connecting with people!' : 'This user hasn\'t added any friends yet.'"
                            />
                        @endif
                    </x-ui.card>
                @endif
            </div>
        </div>
    @endif
</div>
