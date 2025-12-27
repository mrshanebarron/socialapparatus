<div class="max-w-2xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">On This Day</h1>

    @if($memories->isEmpty())
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No memories for today</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Check back tomorrow for new memories!</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($memories as $memory)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 px-4 py-2">
                        <span class="text-white font-semibold">{{ $memory->years_ago }} {{ Str::plural('year', $memory->years_ago) }} ago</span>
                    </div>

                    <div class="p-4">
                        @if($memory->memorable)
                            <div class="flex items-start space-x-3">
                                <img src="{{ $memory->user->profile_photo_url }}" alt="{{ $memory->user->name }}" class="w-10 h-10 rounded-full">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $memory->user->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $memory->memory_date->format('F j, Y') }}</p>

                                    @if($memory->memorable_type === 'App\Models\Post')
                                        <p class="mt-2 text-gray-700 dark:text-gray-300">{{ Str::limit($memory->memorable->content, 200) }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="mt-4 flex items-center space-x-2">
                            <button wire:click="share({{ $memory->id }})" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                Share
                            </button>
                            <button wire:click="hide({{ $memory->id }})" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                Hide
                            </button>
                            <button wire:click="dismiss({{ $memory->id }})" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                Dismiss
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
