<div>
    <!-- Story Bar -->
    @livewire('stories.story-bar')

    <!-- Filter Tabs -->
    <div class="mb-6">
        <x-ui.card padding="none">
            <nav class="flex" aria-label="Tabs">
                <button
                    wire:click="setFilter('feed')"
                    class="flex-1 py-4 px-4 text-center font-medium text-sm transition-all duration-200 relative {{ $filter === 'feed' ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-300' }}"
                >
                    <span class="flex items-center justify-center gap-2">
                        <x-heroicon-o-home class="w-4 h-4" />
                        My Feed
                    </span>
                    @if($filter === 'feed')
                        <span class="absolute bottom-0 inset-x-4 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full"></span>
                    @endif
                </button>
                <button
                    wire:click="setFilter('discover')"
                    class="flex-1 py-4 px-4 text-center font-medium text-sm transition-all duration-200 relative {{ $filter === 'discover' ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-300' }}"
                >
                    <span class="flex items-center justify-center gap-2">
                        <x-heroicon-o-globe-alt class="w-4 h-4" />
                        Discover
                    </span>
                    @if($filter === 'discover')
                        <span class="absolute bottom-0 inset-x-4 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full"></span>
                    @endif
                </button>
                <button
                    wire:click="setFilter('my-posts')"
                    class="flex-1 py-4 px-4 text-center font-medium text-sm transition-all duration-200 relative {{ $filter === 'my-posts' ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-300' }}"
                >
                    <span class="flex items-center justify-center gap-2">
                        <x-heroicon-o-user class="w-4 h-4" />
                        My Posts
                    </span>
                    @if($filter === 'my-posts')
                        <span class="absolute bottom-0 inset-x-4 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full"></span>
                    @endif
                </button>
            </nav>
        </x-ui.card>
    </div>

    <!-- Create Post -->
    <livewire:feed.create-post />

    <!-- Posts List -->
    <div class="space-y-4 mt-6" wire:poll.30s>
        @forelse ($posts as $post)
            <livewire:feed.post-card :post="$post" :key="$post->id" />
        @empty
            <x-ui.card>
                <x-ui.empty-state
                    icon="document-text"
                    title="No posts yet"
                    :description="match($filter) {
                        'feed' => 'Follow more people or add friends to see their posts here.',
                        'discover' => 'Be the first to create a public post!',
                        default => 'You haven\'t created any posts yet.'
                    }"
                />
            </x-ui.card>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($posts->hasPages())
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    @endif
</div>
