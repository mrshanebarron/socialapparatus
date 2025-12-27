<div class="max-w-2xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">On This Day</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Your memories from {{ $today }} in previous years</p>
    </div>

    @if($memories->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No memories today</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You don't have any posts from {{ $today }} in previous years.</p>
            <div class="mt-6">
                <a href="{{ route('feed') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Create a Memory Today
                </a>
            </div>
        </div>
    @else
        <div class="space-y-8">
            @foreach($memories as $year => $yearPosts)
                <div>
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-600 text-white px-4 py-2 rounded-full text-sm font-medium">
                            {{ now()->year - $year }} {{ Str::plural('year', now()->year - $year) }} ago
                        </div>
                        <div class="ml-3 text-gray-500 dark:text-gray-400">{{ $year }}</div>
                    </div>
                    <div class="space-y-4">
                        @foreach($yearPosts as $post)
                            <livewire:feed.post-card :post="$post" :key="'memory-'.$post->id" />
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
