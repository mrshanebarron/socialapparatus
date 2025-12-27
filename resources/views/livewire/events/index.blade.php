<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-surface-900 dark:text-white">Events</h1>
        <a wire:navigate href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-xl transition-colors">
            <x-heroicon-o-plus class="w-5 h-5 mr-2" />
            Create Event
        </a>
    </div>

    <!-- Filter Tabs -->
    <x-ui.card padding="none">
        <nav class="flex" aria-label="Tabs">
            <button wire:click="setFilter('upcoming')"
                    class="flex-1 py-4 px-4 text-center font-medium text-sm transition-all duration-200 relative {{ $filter === 'upcoming' ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-300' }}">
                Upcoming
                @if($filter === 'upcoming')
                    <span class="absolute bottom-0 inset-x-4 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full"></span>
                @endif
            </button>
            <button wire:click="setFilter('going')"
                    class="flex-1 py-4 px-4 text-center font-medium text-sm transition-all duration-200 relative {{ $filter === 'going' ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-300' }}">
                Going
                @if($filter === 'going')
                    <span class="absolute bottom-0 inset-x-4 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full"></span>
                @endif
            </button>
            <button wire:click="setFilter('my-events')"
                    class="flex-1 py-4 px-4 text-center font-medium text-sm transition-all duration-200 relative {{ $filter === 'my-events' ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-300' }}">
                My Events
                @if($filter === 'my-events')
                    <span class="absolute bottom-0 inset-x-4 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full"></span>
                @endif
            </button>
            <button wire:click="setFilter('past')"
                    class="flex-1 py-4 px-4 text-center font-medium text-sm transition-all duration-200 relative {{ $filter === 'past' ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-300' }}">
                Past
                @if($filter === 'past')
                    <span class="absolute bottom-0 inset-x-4 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full"></span>
                @endif
            </button>
        </nav>
    </x-ui.card>

    <!-- Events Grid -->
    <div class="grid gap-4 md:grid-cols-2">
        @forelse($events as $event)
            <a wire:navigate href="{{ route('events.show', $event) }}">
                <x-ui.card class="overflow-hidden hover:shadow-lg transition-shadow h-full">
                    <!-- Cover Image -->
                    @if($event->cover_image)
                        <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-32 object-cover -mx-5 -mt-5 mb-4">
                    @else
                        <div class="w-full h-32 bg-gradient-to-r from-primary-500 to-accent-500 flex items-center justify-center -mx-5 -mt-5 mb-4">
                            <x-heroicon-o-calendar-days class="w-12 h-12 text-white/50" />
                        </div>
                    @endif

                    <!-- Date Badge -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 text-center bg-primary-100 dark:bg-primary-900 rounded-xl p-2 w-14">
                            <span class="block text-xs font-semibold text-primary-600 dark:text-primary-400 uppercase">{{ $event->starts_at->format('M') }}</span>
                            <span class="block text-xl font-bold text-primary-700 dark:text-primary-300">{{ $event->starts_at->format('d') }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-semibold text-surface-900 dark:text-white truncate">{{ $event->title }}</h3>
                            <p class="text-sm text-surface-500 dark:text-surface-400">
                                {{ $event->starts_at->format('D, M j \a\t g:i A') }}
                            </p>
                            <p class="text-sm text-surface-500 dark:text-surface-400 flex items-center mt-1">
                                @if($event->is_online)
                                    <x-heroicon-o-video-camera class="w-4 h-4 mr-1" />
                                    Online Event
                                @else
                                    <x-heroicon-o-map-pin class="w-4 h-4 mr-1" />
                                    {{ Str::limit($event->location, 30) }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Host & Attendees -->
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-surface-100 dark:border-surface-700">
                        <div class="flex items-center space-x-2">
                            <img src="{{ $event->user->profile_photo_url }}" alt="{{ $event->user->name }}" class="w-6 h-6 rounded-full">
                            <span class="text-sm text-surface-600 dark:text-surface-400">{{ $event->user->name }}</span>
                        </div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">
                            {{ $event->going_count }} going
                        </div>
                    </div>
                </x-ui.card>
            </a>
        @empty
            <div class="col-span-2">
                <x-ui.card>
                    <x-ui.empty-state
                        icon="calendar-days"
                        title="No events found"
                        :description="match($filter) {
                            'upcoming' => 'There are no upcoming events. Why not create one?',
                            'going' => 'You haven\'t RSVP\'d to any events yet.',
                            'my-events' => 'You haven\'t created any events yet.',
                            default => 'There are no past events.'
                        }"
                        :action="$filter !== 'past' ? route('events.create') : null"
                        :actionText="$filter !== 'past' ? 'Create Event' : null"
                    />
                </x-ui.card>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $events->links() }}
    </div>
</div>
