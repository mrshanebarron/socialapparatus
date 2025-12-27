<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Post Scheduling</h1>
        <a href="{{ route('scheduling.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            Schedule Post
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg">{{ session('success') }}</div>
    @endif

    <!-- Tabs -->
    <div class="flex space-x-2 mb-6">
        <button wire:click="setTab('scheduled')" class="px-4 py-2 rounded-lg {{ $tab === 'scheduled' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
            Scheduled
        </button>
        <button wire:click="setTab('drafts')" class="px-4 py-2 rounded-lg {{ $tab === 'drafts' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
            Drafts
        </button>
    </div>

    @if($tab === 'scheduled')
        @if($scheduledPosts->isEmpty())
            <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No scheduled posts</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Schedule posts to publish at optimal times.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($scheduledPosts as $post)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-gray-900 dark:text-white">{{ Str::limit($post->content, 150) }}</p>
                                <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $post->scheduled_for->format('M j, Y g:i A') }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-xs {{ $post->status === 'scheduled' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </div>
                            </div>
                            @if($post->status === 'scheduled')
                                <button wire:click="cancelScheduled({{ $post->id }})" class="text-red-600 hover:text-red-700 text-sm">
                                    Cancel
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">{{ $scheduledPosts->links() }}</div>
        @endif
    @else
        @if($drafts->isEmpty())
            <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No drafts</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Your auto-saved drafts will appear here.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($drafts as $draft)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-gray-900 dark:text-white">{{ Str::limit($draft->content, 150) }}</p>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Last edited {{ $draft->last_edited_at->diffForHumans() }}
                                </p>
                            </div>
                            <button wire:click="deleteDraft({{ $draft->id }})" class="text-red-600 hover:text-red-700 text-sm">
                                Delete
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">{{ $drafts->links() }}</div>
        @endif
    @endif
</div>
