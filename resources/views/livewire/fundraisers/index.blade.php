<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Fundraisers</h1>
            <p class="text-gray-600 dark:text-gray-400">Support causes that matter to you</p>
        </div>
        <a href="{{ route('fundraisers.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Start a Fundraiser
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search fundraisers..."
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <select wire:model.live="category" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">All Categories</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select wire:model.live="sortBy" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="trending">Trending</option>
                    <option value="newest">Newest</option>
                    <option value="most_raised">Most Raised</option>
                    <option value="ending_soon">Ending Soon</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Fundraisers Grid -->
    @if($fundraisers->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No fundraisers found</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Be the first to start one!</p>
            <a href="{{ route('fundraisers.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Start a Fundraiser
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($fundraisers as $fundraiser)
                <a href="{{ route('fundraisers.show', $fundraiser) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition group">
                    <div class="aspect-video bg-gray-100 dark:bg-gray-700 relative">
                        @if($fundraiser->cover_image_url)
                            <img src="{{ $fundraiser->cover_image_url }}" alt="{{ $fundraiser->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-2 left-2">
                            <span class="px-2 py-1 bg-black/50 text-white text-xs rounded-full">
                                {{ $categories[$fundraiser->category] ?? 'Other' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 line-clamp-2">
                            {{ $fundraiser->title }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                            {{ Str::limit(strip_tags($fundraiser->story), 100) }}
                        </p>

                        <!-- Progress Bar -->
                        <div class="mt-4">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $fundraiser->formatted_raised }}</span>
                                <span class="text-gray-500 dark:text-gray-400">of {{ $fundraiser->formatted_goal }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ min(100, $fundraiser->progress_percent) }}%"></div>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="mt-3 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>{{ $fundraiser->donors_count }} donors</span>
                            @if($fundraiser->ends_at)
                                @if($fundraiser->hasEnded())
                                    <span class="text-red-500">Ended</span>
                                @else
                                    <span>{{ $fundraiser->ends_at->diffForHumans() }}</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $fundraisers->links() }}
        </div>
    @endif
</div>
