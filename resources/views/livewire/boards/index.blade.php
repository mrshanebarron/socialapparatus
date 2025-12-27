<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Boards</h1>
        <a href="{{ route('boards.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            + Create Board
        </a>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-4 mb-6">
        <div class="flex rounded-lg overflow-hidden border dark:border-gray-700">
            <button wire:click="$set('filter', 'all')" class="px-4 py-2 text-sm {{ $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">All</button>
            <button wire:click="$set('filter', 'owned')" class="px-4 py-2 text-sm {{ $filter === 'owned' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">My Boards</button>
            <button wire:click="$set('filter', 'shared')" class="px-4 py-2 text-sm {{ $filter === 'shared' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">Shared</button>
        </div>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search boards..."
               class="flex-1 min-w-[200px] rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800">
    </div>

    <!-- Boards Grid -->
    @if($boards->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No boards yet</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">Create your first board to start organizing.</p>
            <a href="{{ route('boards.create') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Create Board
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($boards as $board)
                <a href="{{ route('boards.show', $board) }}" class="block bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                    @if($board->cover_image)
                        <img src="{{ Storage::url($board->cover_image) }}" alt="{{ $board->name }}" class="w-full h-32 object-cover">
                    @else
                        <div class="w-full h-32 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <span class="text-4xl text-white">{{ substr($board->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white truncate">{{ $board->name }}</h3>
                            <span class="text-xs px-2 py-1 rounded-full {{ $board->visibility === 'public' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                {{ ucfirst($board->visibility) }}
                            </span>
                        </div>
                        @if($board->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">{{ $board->description }}</p>
                        @endif
                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center space-x-2">
                                <img src="{{ $board->user->profile_photo_url }}" class="w-6 h-6 rounded-full">
                                <span>{{ $board->user->name }}</span>
                            </div>
                            <span>{{ $board->items_count }} items</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $boards->links() }}
        </div>
    @endif
</div>
