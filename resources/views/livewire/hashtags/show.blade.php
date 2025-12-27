<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex gap-6">
        <!-- Main Content -->
        <div class="flex-1 max-w-2xl">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">#{{ $hashtag->tag }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ number_format($hashtag->posts_count) }} posts</p>
            </div>

            <!-- Posts -->
            @if($posts->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No posts found</h3>
                    <p class="text-gray-600 dark:text-gray-400">Be the first to post with #{{ $hashtag->tag }}</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($posts as $post)
                        <livewire:feed.post-card :post="$post" :wire:key="'post-'.$post->id" />
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="w-80 hidden lg:block">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sticky top-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Trending Hashtags</h3>
                <div class="space-y-3">
                    @foreach($trendingHashtags as $trending)
                        <a href="{{ route('hashtag.show', $trending->tag) }}"
                           class="block hover:bg-gray-50 dark:hover:bg-gray-700 -mx-2 px-2 py-2 rounded-lg transition {{ $trending->id === $hashtag->id ? 'bg-indigo-50 dark:bg-indigo-900/50' : '' }}">
                            <p class="font-medium text-indigo-600 dark:text-indigo-400">#{{ $trending->tag }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($trending->weekly_count) }} posts this week</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
