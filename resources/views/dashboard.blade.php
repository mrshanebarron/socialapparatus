<x-layouts.spa>
    <!-- Create Post Card -->
    <livewire:feed.create-post />

    <!-- Stories Section -->
    @livewire('stories.story-bar')

    <!-- Activity Feed -->
    <div class="space-y-4">
        @php
            $posts = \App\Models\Post::feed(auth()->user())
                ->with(['user.profile', 'topLevelComments.user.profile'])
                ->take(10)
                ->get();
        @endphp

        @forelse ($posts as $post)
            <livewire:feed.post-card :post="$post" :key="'dashboard-'.$post->id" />
        @empty
            <x-ui.card>
                <x-ui.empty-state
                    icon="document-text"
                    title="No posts yet"
                    description="Follow more people or add friends to see their posts here."
                />
            </x-ui.card>
        @endforelse

        @if($posts->count() >= 10)
            <div class="text-center">
                <a wire:navigate href="{{ route('feed.index') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors">
                    View More Posts
                    <x-heroicon-o-arrow-right class="w-5 h-5 ml-2" />
                </a>
            </div>
        @endif
    </div>
</x-layouts.spa>
