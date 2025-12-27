<div class="max-w-2xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Saved Posts</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Posts you've saved for later</p>
    </div>

    @if($savedPosts->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No saved posts</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start saving posts to view them here later.</p>
            <div class="mt-6">
                <a href="{{ route('feed') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Browse Feed
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($savedPosts as $savedPost)
                @if($savedPost->post)
                    <div class="relative">
                        <livewire:feed.post-card :post="$savedPost->post" :key="'saved-'.$savedPost->id" />
                        <div class="absolute top-2 right-12 z-10">
                            <button wire:click="unsavePost({{ $savedPost->id }})"
                                    wire:confirm="Remove this post from saved?"
                                    class="p-1 bg-white dark:bg-gray-800 rounded-full shadow-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                    title="Remove from saved">
                                <svg class="h-5 w-5 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                </svg>
                            </button>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 px-4">
                            Saved {{ $savedPost->created_at->diffForHumans() }}
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="mt-6">
            {{ $savedPosts->links() }}
        </div>
    @endif
</div>
