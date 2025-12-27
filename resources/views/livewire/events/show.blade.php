<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('events.index') }}" wire:navigate class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Events
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <!-- Cover Image -->
        @if($event->cover_image)
            <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover">
        @else
            <div class="w-full h-64 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                <svg class="w-24 h-24 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        @endif

        <div class="p-6">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 text-center bg-indigo-100 dark:bg-indigo-900 rounded-lg p-3 w-16">
                        <span class="block text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase">{{ $event->starts_at->format('M') }}</span>
                        <span class="block text-2xl font-bold text-indigo-700 dark:text-indigo-300">{{ $event->starts_at->format('d') }}</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $event->title }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            {{ $event->starts_at->format('l, F j, Y \a\t g:i A') }}
                            @if($event->ends_at)
                                - {{ $event->ends_at->format('g:i A') }}
                            @endif
                        </p>
                    </div>
                </div>

                <!-- RSVP Buttons -->
                @auth
                    @if($event->user_id !== auth()->id())
                        <div class="flex space-x-2">
                            <button wire:click="rsvp('going')"
                                    class="px-4 py-2 rounded-lg text-sm font-medium {{ $userRsvp === 'going' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                Going
                            </button>
                            <button wire:click="rsvp('interested')"
                                    class="px-4 py-2 rounded-lg text-sm font-medium {{ $userRsvp === 'interested' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                Interested
                            </button>
                            @if($userRsvp)
                                <button wire:click="rsvp('not_going')"
                                        class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                                    Can't Go
                                </button>
                            @endif
                        </div>
                    @else
                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-sm rounded-lg">
                            You're hosting
                        </span>
                    @endif
                @endauth
            </div>

            <!-- Info Cards -->
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <!-- Location/Link -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        @if($event->is_online)
                            <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Online Event</p>
                                @if($event->online_link && ($userRsvp === 'going' || $event->user_id === auth()->id()))
                                    <a href="{{ $event->online_link }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                                        Join Link
                                    </a>
                                @else
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Link available after RSVP</p>
                                @endif
                            </div>
                        @else
                            <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Location</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $event->location }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Host -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $event->user->profile_photo_url }}" alt="{{ $event->user->name }}" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Hosted by</p>
                            <a href="{{ route('profile.view', $event->user) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">
                                {{ $event->user->name }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($event->description)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">About</h2>
                    <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>
            @endif

            <!-- Attendees -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $event->going_count }} Going · {{ $event->interested_count }} Interested
                    </h2>
                </div>

                @if($event->goingUsers->count() > 0)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Going</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($event->goingUsers as $rsvp)
                                <a href="{{ route('profile.view', $rsvp->user) }}" class="flex items-center space-x-2 bg-gray-100 dark:bg-gray-700 rounded-full pr-3 hover:bg-gray-200 dark:hover:bg-gray-600">
                                    <img src="{{ $rsvp->user->profile_photo_url }}" alt="{{ $rsvp->user->name }}" class="w-8 h-8 rounded-full">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $rsvp->user->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($event->interestedUsers->count() > 0)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Interested</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($event->interestedUsers as $rsvp)
                                <a href="{{ route('profile.view', $rsvp->user) }}" class="flex items-center space-x-2 bg-gray-100 dark:bg-gray-700 rounded-full pr-3 hover:bg-gray-200 dark:hover:bg-gray-600">
                                    <img src="{{ $rsvp->user->profile_photo_url }}" alt="{{ $rsvp->user->name }}" class="w-8 h-8 rounded-full">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $rsvp->user->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
