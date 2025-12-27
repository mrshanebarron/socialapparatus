<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pages</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Discover businesses, brands, and public figures</p>
        </div>
        <a href="{{ route('pages.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
            Create Page
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Search pages..."
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <select wire:model.live="category"
                        class="w-full sm:w-48 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Pages Grid -->
    @if($pages->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No pages found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                @if($search || $category)
                    Try adjusting your search or filter.
                @else
                    Be the first to create a page!
                @endif
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($pages as $page)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <!-- Cover Image -->
                    <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
                        @if($page->cover_image_url)
                            <img src="{{ $page->cover_image_url }}" alt="" class="w-full h-full object-cover">
                        @endif
                    </div>

                    <!-- Page Info -->
                    <div class="p-4 -mt-8 relative">
                        <a href="{{ route('pages.show', $page) }}" class="block">
                            <img src="{{ $page->avatar_url }}" alt="{{ $page->name }}"
                                 class="h-16 w-16 rounded-lg border-4 border-white dark:border-gray-800 object-cover bg-white">
                        </a>
                        <div class="mt-2">
                            <a href="{{ route('pages.show', $page) }}" class="font-semibold text-gray-900 dark:text-white hover:underline">
                                {{ $page->name }}
                                @if($page->is_verified)
                                    <svg class="inline h-4 w-4 text-blue-500 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </a>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $page->category }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                {{ number_format($page->followers_count) }} {{ Str::plural('follower', $page->followers_count) }}
                            </p>
                        </div>
                        @if($page->description)
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 line-clamp-2">{{ $page->description }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $pages->links() }}
        </div>
    @endif
</div>
