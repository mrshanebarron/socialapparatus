<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('marketplace.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Marketplace
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Images -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                @if($listing->images && count($listing->images) > 0)
                    <div class="relative aspect-video bg-gray-100 dark:bg-gray-700">
                        <img src="{{ Storage::url($listing->images[$currentImageIndex]) }}" alt="{{ $listing->title }}" class="w-full h-full object-contain">
                        @if(count($listing->images) > 1)
                            <button wire:click="previousImage" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/50 text-white p-2 rounded-full hover:bg-black/70">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button wire:click="nextImage" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/50 text-white p-2 rounded-full hover:bg-black/70">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        @endif
                        @if($listing->status === 'sold')
                            <div class="absolute top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-full font-bold">SOLD</div>
                        @endif
                    </div>
                    @if(count($listing->images) > 1)
                        <div class="p-4 flex space-x-2 overflow-x-auto">
                            @foreach($listing->images as $index => $image)
                                <button wire:click="setImage({{ $index }})" class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden {{ $currentImageIndex === $index ? 'ring-2 ring-indigo-500' : 'opacity-70 hover:opacity-100' }}">
                                    <img src="{{ Storage::url($image) }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="aspect-video bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif

                <!-- Description -->
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Description</h2>
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $listing->description }}</p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Price & Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $listing->formatted_price }}</p>
                @if($listing->is_negotiable)
                    <span class="inline-flex items-center px-2 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full mt-2">
                        Negotiable
                    </span>
                @endif
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white mt-4">{{ $listing->title }}</h1>

                <div class="mt-4 space-y-2 text-sm">
                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        {{ $listing->category?->name ?? 'Uncategorized' }}
                    </div>
                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ \App\Models\MarketplaceListing::CONDITIONS[$listing->condition] ?? 'Unknown' }} condition
                    </div>
                    @if($listing->location_name)
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            {{ $listing->location_name }}
                        </div>
                    @endif
                    @if($listing->is_shipping_available)
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            Shipping available
                        </div>
                    @endif
                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Listed {{ $listing->created_at->diffForHumans() }}
                    </div>
                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{ $listing->views_count }} views
                    </div>
                </div>

                @auth
                    @if($listing->user_id === auth()->id())
                        <div class="mt-6 space-y-2">
                            @if($listing->status === 'active')
                                <button wire:click="markAsSold" wire:confirm="Mark this item as sold?" class="w-full py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    Mark as Sold
                                </button>
                            @endif
                            <button wire:click="deleteListing" wire:confirm="Are you sure you want to delete this listing?" class="w-full py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Delete Listing
                            </button>
                        </div>
                    @elseif($listing->status === 'active')
                        <button wire:click="$set('showMessageModal', true)" class="w-full mt-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                            Message Seller
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block w-full mt-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium text-center">
                        Log in to Contact Seller
                    </a>
                @endauth
            </div>

            <!-- Seller Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">Seller Information</h3>
                <a href="{{ route('profile.view', $listing->user->profile?->username ?? $listing->user->id) }}" class="flex items-center space-x-3 hover:bg-gray-50 dark:hover:bg-gray-700 -m-2 p-2 rounded-lg">
                    <img src="{{ $listing->user->profile_photo_url }}" alt="{{ $listing->user->name }}" class="w-12 h-12 rounded-full">
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $listing->user->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Member since {{ $listing->user->created_at->format('M Y') }}</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Related Listings -->
    @if($relatedListings->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Related Listings</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($relatedListings as $related)
                    <a href="{{ route('marketplace.show', $related) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                        <div class="aspect-square bg-gray-100 dark:bg-gray-700">
                            @if($related->images && count($related->images) > 0)
                                <img src="{{ Storage::url($related->images[0]) }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="font-bold text-gray-900 dark:text-white">{{ $related->formatted_price }}</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-1">{{ $related->title }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Message Modal -->
    @if($showMessageModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50" wire:click="$set('showMessageModal', false)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Message Seller</h3>
                        <button wire:click="$set('showMessageModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-400">About: <span class="font-medium text-gray-900 dark:text-white">{{ $listing->title }}</span></p>
                    </div>
                    <textarea wire:model="message" rows="4" placeholder="Hi, is this still available?"
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                    <button wire:click="sendMessage" class="w-full mt-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Send Message
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
