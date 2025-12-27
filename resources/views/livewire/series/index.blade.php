<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Content Series</h1>
        <a href="{{ route('series.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            + Create Series
        </a>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-4 mb-6">
        <div class="flex rounded-lg overflow-hidden border dark:border-gray-700">
            <button wire:click="$set('filter', 'all')" class="px-4 py-2 text-sm {{ $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">All</button>
            <button wire:click="$set('filter', 'following')" class="px-4 py-2 text-sm {{ $filter === 'following' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">Following</button>
            <button wire:click="$set('filter', 'mine')" class="px-4 py-2 text-sm {{ $filter === 'mine' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">My Series</button>
        </div>
        <select wire:model.live="type" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm">
            <option value="">All Types</option>
            <option value="playlist">Playlist</option>
            <option value="course">Course</option>
            <option value="documentary">Documentary</option>
            <option value="tutorial">Tutorial</option>
        </select>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search series..."
               class="flex-1 min-w-[200px] rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm">
    </div>

    <!-- Series Grid -->
    @if($series->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No series found</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">Create your first series to organize your content.</p>
            <a href="{{ route('series.create') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Create Series
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($series as $s)
                <a href="{{ route('series.show', $s) }}" class="block bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition overflow-hidden group">
                    <div class="relative">
                        @if($s->cover_image)
                            <img src="{{ Storage::url($s->cover_image) }}" alt="{{ $s->title }}" class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-2 right-2 px-2 py-1 bg-black/50 text-white text-xs rounded">
                            {{ $s->items_count }} items
                        </div>
                        <div class="absolute bottom-2 left-2 px-2 py-1 bg-black/50 text-white text-xs rounded capitalize">
                            {{ $s->type }}
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 truncate">
                            {{ $s->title }}
                        </h3>
                        @if($s->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mt-1">{{ $s->description }}</p>
                        @endif
                        <div class="flex items-center justify-between mt-3 text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center space-x-2">
                                <img src="{{ $s->user->profile_photo_url }}" class="w-6 h-6 rounded-full">
                                <span>{{ $s->user->name }}</span>
                            </div>
                            <span>{{ $s->followers_count }} followers</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $series->links() }}
        </div>
    @endif
</div>
