<div class="max-w-4xl mx-auto">
    <!-- Cover & Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <!-- Cover Image -->
        <div class="h-48 sm:h-64 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
            @if($page->cover_image_url)
                <img src="{{ $page->cover_image_url }}" alt="" class="w-full h-full object-cover">
            @endif
        </div>

        <!-- Page Info -->
        <div class="px-4 sm:px-6 pb-4">
            <div class="flex flex-col sm:flex-row sm:items-end -mt-12 sm:-mt-16">
                <img src="{{ $page->avatar_url }}" alt="{{ $page->name }}"
                     class="h-24 w-24 sm:h-32 sm:w-32 rounded-xl border-4 border-white dark:border-gray-800 object-cover bg-white">
                <div class="mt-4 sm:mt-0 sm:ml-4 sm:mb-2 flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                        {{ $page->name }}
                        @if($page->is_verified)
                            <svg class="h-6 w-6 text-blue-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400">{{ $page->category }}</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
                        {{ number_format($page->followers_count) }} {{ Str::plural('follower', $page->followers_count) }}
                        @if($page->likes_count > 0)
                            &middot; {{ number_format($page->likes_count) }} {{ Str::plural('like', $page->likes_count) }}
                        @endif
                    </p>
                </div>
                <div class="mt-4 sm:mt-0">
                    @auth
                        <button wire:click="toggleFollow"
                                class="{{ $isFollowing ? 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' : 'bg-indigo-600 text-white' }} px-6 py-2 rounded-lg font-medium hover:opacity-90">
                            {{ $isFollowing ? 'Following' : 'Follow' }}
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700">
                            Follow
                        </a>
                    @endauth
                </div>
            </div>

            @if($page->description)
                <p class="mt-4 text-gray-700 dark:text-gray-300">{{ $page->description }}</p>
            @endif

            <!-- Contact Info -->
            @if($page->website || $page->email || $page->phone || $page->address)
                <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-500 dark:text-gray-400">
                    @if($page->website)
                        <a href="{{ $page->website }}" target="_blank" class="flex items-center hover:text-indigo-600 dark:hover:text-indigo-400">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                            {{ parse_url($page->website, PHP_URL_HOST) }}
                        </a>
                    @endif
                    @if($page->email)
                        <a href="mailto:{{ $page->email }}" class="flex items-center hover:text-indigo-600 dark:hover:text-indigo-400">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $page->email }}
                        </a>
                    @endif
                    @if($page->phone)
                        <a href="tel:{{ $page->phone }}" class="flex items-center hover:text-indigo-600 dark:hover:text-indigo-400">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ $page->phone }}
                        </a>
                    @endif
                    @if($page->address)
                        <span class="flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $page->address }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Posts -->
    <div class="mt-6 space-y-4">
        @forelse($posts as $post)
            <livewire:feed.post-card :post="$post" :key="'page-post-'.$post->id" />
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No posts yet</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This page hasn't posted anything yet.</p>
            </div>
        @endforelse

        @if($posts->hasPages())
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
