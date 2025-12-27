<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-surface-900 dark:text-white">Marketplace</h1>
            <p class="text-surface-600 dark:text-surface-400">Buy and sell items in your community</p>
        </div>
        <a wire:navigate href="{{ route('marketplace.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors">
            <x-heroicon-o-plus class="w-5 h-5 mr-2" />
            Sell Something
        </a>
    </div>

    <!-- Filters -->
    <x-ui.card>
        <div class="flex flex-col sm:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search listings..."
                       class="w-full rounded-xl border-surface-300 dark:border-surface-600 dark:bg-surface-700 dark:text-white text-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            <!-- Category Select -->
            <select wire:model.live="categoryId" class="rounded-xl border-surface-300 dark:border-surface-600 dark:bg-surface-700 dark:text-white text-sm focus:border-primary-500 focus:ring-primary-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->icon }} {{ $category->name }}</option>
                @endforeach
            </select>
            <!-- Sort -->
            <select wire:model.live="sortBy" class="rounded-xl border-surface-300 dark:border-surface-600 dark:bg-surface-700 dark:text-white text-sm focus:border-primary-500 focus:ring-primary-500">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="price_low">Price: Low to High</option>
                <option value="price_high">Price: High to Low</option>
            </select>
        </div>
    </x-ui.card>

    <!-- Listings Grid -->
    @if($listings->isEmpty())
        <x-ui.card>
            <x-ui.empty-state
                icon="shopping-bag"
                title="No listings found"
                description="Be the first to sell something!"
                action="{{ route('marketplace.create') }}"
                actionText="Create Listing"
            />
        </x-ui.card>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($listings as $listing)
                <a wire:navigate href="{{ route('marketplace.show', $listing) }}">
                    <x-ui.card padding="none" class="overflow-hidden hover:shadow-lg transition group h-full">
                        <div class="aspect-square bg-surface-100 dark:bg-surface-700 relative">
                            @if($listing->images && count($listing->images) > 0)
                                <img src="{{ Storage::url($listing->images[0]) }}" alt="{{ $listing->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <x-heroicon-o-photo class="w-12 h-12 text-surface-400" />
                                </div>
                            @endif
                            @if($listing->status === 'sold')
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                    <span class="bg-red-500 text-white px-4 py-2 rounded-full font-bold">SOLD</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="text-lg font-bold text-surface-900 dark:text-white">{{ $listing->formatted_price }}</p>
                            <h3 class="text-sm text-surface-700 dark:text-surface-300 line-clamp-2 group-hover:text-primary-600 dark:group-hover:text-primary-400">{{ $listing->title }}</h3>
                            <div class="mt-2 flex items-center text-xs text-surface-500 dark:text-surface-400">
                                @if($listing->location_name)
                                    <x-heroicon-o-map-pin class="w-3 h-3 mr-1" />
                                    {{ Str::limit($listing->location_name, 20) }}
                                @else
                                    {{ $listing->created_at->diffForHumans() }}
                                @endif
                            </div>
                        </div>
                    </x-ui.card>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $listings->links() }}
        </div>
    @endif
</div>
