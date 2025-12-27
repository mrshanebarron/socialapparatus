<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Collections</h1>
            @if($isOwner)
                <a href="{{ route('collections.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    New Collection
                </a>
            @endif
        </div>

        @if($collections->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No collections</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if($isOwner)
                        Create collections to save and organize your favorite content.
                    @else
                        This user hasn't created any public collections yet.
                    @endif
                </p>
                @if($isOwner)
                    <div class="mt-6">
                        <a href="{{ route('collections.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                            Create Collection
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($collections as $collection)
                    <a href="{{ route('collections.show', $collection) }}" class="block bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow">
                        @if($collection->cover_image)
                            <img src="{{ Storage::url($collection->cover_image) }}" alt="" class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                <svg class="h-12 w-12 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        @endif
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <h3 class="font-medium text-gray-900 dark:text-white">{{ $collection->name }}</h3>
                                @if($collection->privacy !== 'public')
                                    <span class="text-xs text-gray-500">
                                        @if($collection->privacy === 'private')
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        @endif
                                    </span>
                                @endif
                            </div>
                            @if($collection->description)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $collection->description }}</p>
                            @endif
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">{{ $collection->items_count }} {{ Str::plural('item', $collection->items_count) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $collections->links() }}
            </div>
        @endif
    </div>
</div>
