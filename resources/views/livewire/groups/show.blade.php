<div class="max-w-5xl mx-auto">
    @if(!$canView)
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-8 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">This group is private</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                You need to be a member to view this group's content.
            </p>
        </div>
    @else
        <!-- Cover Photo -->
        <div class="relative h-56 md:h-72 lg:h-80 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 rounded-xl overflow-hidden shadow-lg">
            @if($group->cover_photo_url)
                <img src="{{ $group->cover_photo_url }}" alt="Cover" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/90 via-purple-600/90 to-pink-500/90"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-24 h-24 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            @endif

            <!-- Group info overlay on cover -->
            <div class="absolute bottom-0 left-0 right-0 p-6">
                <div class="flex items-end space-x-4">
                    <img class="h-24 w-24 md:h-32 md:w-32 rounded-xl ring-4 ring-white dark:ring-gray-900 object-cover shadow-xl"
                         src="{{ $group->avatar_url }}"
                         alt="{{ $group->name }}">
                    <div class="flex-1 pb-2">
                        <h1 class="text-2xl md:text-3xl font-bold text-white drop-shadow-lg">
                            {{ $group->name }}
                        </h1>
                        <div class="flex items-center mt-1 space-x-3 text-sm text-white/90">
                            <span class="inline-flex items-center">
                                @if($group->privacy === 'public')
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Public Group
                                @elseif($group->privacy === 'private')
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Private Group
                                @else
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                    Secret Group
                                @endif
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ number_format($group->members_count) }} {{ Str::plural('member', $group->members_count) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl mt-4 px-4 py-3">
            <div class="flex items-center justify-between">
                <!-- Tabs -->
                <div class="flex space-x-1">
                    <button wire:click="setTab('discussion')"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'discussion' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                        Discussion
                    </button>
                    <button wire:click="setTab('members')"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'members' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                        Members
                    </button>
                    <button wire:click="setTab('about')"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'about' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                        About
                    </button>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-2">
                    @auth
                        @if($isAdmin)
                            <a href="{{ route('groups.members', $group) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Manage
                            </a>
                        @elseif($isMember)
                            <button wire:click="leaveGroup" type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900/30 rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Joined
                            </button>
                        @elseif($isPending)
                            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-yellow-700 dark:text-yellow-400 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pending
                            </span>
                        @else
                            <button wire:click="joinGroup" type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                Join Group
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                            Login to Join
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Sidebar -->
            <div class="lg:col-span-1 space-y-4">
                <!-- About Card -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-5">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">About</h3>
                    @if($group->description)
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">{{ $group->description }}</p>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 italic text-sm">No description provided.</p>
                    @endif

                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 space-y-3">
                        <div class="flex items-center text-sm">
                            <svg class="flex-shrink-0 mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">Created {{ $group->created_at->format('F j, Y') }}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="flex-shrink-0 mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">{{ number_format($group->members_count) }} members</span>
                        </div>
                    </div>
                </div>

                <!-- Admins Card -->
                @if($admins->count() > 0)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-5">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Admins & Moderators</h3>
                        <div class="space-y-3">
                            @foreach($admins as $admin)
                                <a href="{{ route('profile.view', $admin->user) }}" class="flex items-center group">
                                    <img class="h-10 w-10 rounded-full object-cover"
                                         src="{{ $admin->user->profile_photo_url }}"
                                         alt="{{ $admin->user->name }}">
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400">{{ $admin->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ $admin->role }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Members Preview Card -->
                @if($canViewContent && $members->count() > 0)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-5">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Members</h3>
                            <a href="{{ route('groups.members', $group) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                See all
                            </a>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($members->take(9) as $member)
                                <a href="{{ route('profile.view', $member->user) }}" class="block" title="{{ $member->user->name }}">
                                    <img class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800 hover:ring-indigo-500 transition-all"
                                         src="{{ $member->user->profile_photo_url }}"
                                         alt="{{ $member->user->name }}">
                                </a>
                            @endforeach
                            @if($group->members_count > 9)
                                <a href="{{ route('groups.members', $group) }}" class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                    +{{ $group->members_count - 9 }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2">
                @if(!$canViewContent)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Members Only</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Join this group to see posts and activity.
                        </p>
                    </div>
                @else
                    <!-- Create Post (if member) -->
                    @if($isMember)
                        <div class="mb-4">
                            @livewire('feed.create-post', ['groupId' => $group->id])
                        </div>
                    @endif

                    <!-- Posts Feed -->
                    @if($posts instanceof \Illuminate\Pagination\LengthAwarePaginator && $posts->count() > 0)
                        <div class="space-y-4">
                            @foreach($posts as $post)
                                <div class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden">
                                    <!-- Post Header -->
                                    <div class="p-4 flex items-start space-x-3">
                                        <a href="{{ route('profile.view', $post->user) }}">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}">
                                        </a>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('profile.view', $post->user) }}" class="font-medium text-gray-900 dark:text-white hover:underline">
                                                {{ $post->user->name }}
                                            </a>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $post->created_at->diffForHumans() }}
                                                @if($post->visibility !== 'public')
                                                    <span class="mx-1">·</span>
                                                    <svg class="inline-block w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Post Body -->
                                    @if($post->body)
                                        <div class="px-4 pb-3">
                                            <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $post->body }}</p>
                                        </div>
                                    @endif

                                    <!-- Post Media -->
                                    @if($post->media && count($post->media) > 0)
                                        <div class="@if(count($post->media) === 1) @elseif(count($post->media) === 2) grid grid-cols-2 gap-0.5 @else grid grid-cols-2 gap-0.5 @endif">
                                            @foreach($post->media as $index => $mediaItem)
                                                @if($index < 4)
                                                    <div class="relative @if(count($post->media) === 1) @elseif(count($post->media) === 3 && $index === 0) col-span-2 @endif aspect-video bg-gray-100 dark:bg-gray-900">
                                                        <img src="{{ Storage::url($mediaItem) }}" alt="" class="w-full h-full object-cover">
                                                        @if($index === 3 && count($post->media) > 4)
                                                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                                                <span class="text-white text-2xl font-bold">+{{ count($post->media) - 4 }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Engagement Stats -->
                                    @if($post->likes_count > 0 || $post->comments_count > 0)
                                        <div class="px-4 py-2 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                                            @if($post->likes_count > 0)
                                                <span class="flex items-center">
                                                    <span class="inline-flex -space-x-1">
                                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-500 text-white text-xs">👍</span>
                                                    </span>
                                                    <span class="ml-1">{{ $post->likes_count }}</span>
                                                </span>
                                            @else
                                                <span></span>
                                            @endif
                                            @if($post->comments_count > 0)
                                                <span>{{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="px-4 py-2 flex items-center justify-around border-t border-gray-100 dark:border-gray-700">
                                        <button class="flex-1 flex items-center justify-center py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                            </svg>
                                            Like
                                        </button>
                                        <button class="flex-1 flex items-center justify-center py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            Comment
                                        </button>
                                        <button class="flex-1 flex items-center justify-center py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                            </svg>
                                            Share
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No posts yet</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Be the first to share something with this group!
                            </p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif
</div>
