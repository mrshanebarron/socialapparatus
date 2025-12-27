<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Watch</h1>
            <p class="text-gray-600 dark:text-gray-400">Discover videos from your community</p>
        </div>
    </div>

    <!-- Category Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-8 px-4" aria-label="Tabs">
                <button wire:click="setCategory('for_you')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $category === 'for_you' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}">
                    For You
                </button>
                <button wire:click="setCategory('following')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $category === 'following' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}">
                    Following
                </button>
                <button wire:click="setCategory('live')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $category === 'live' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}">
                    <span class="inline-flex items-center">
                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></span>
                        Live
                    </span>
                </button>
                @auth
                    <button wire:click="setCategory('saved')"
                            class="py-4 px-1 border-b-2 font-medium text-sm {{ $category === 'saved' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}">
                        Saved
                    </button>
                @endauth
            </nav>
        </div>
    </div>

    <!-- Videos Grid -->
    @if($videos->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No videos yet</h3>
            <p class="text-gray-600 dark:text-gray-400">
                @if($category === 'following')
                    Videos from people you follow will appear here.
                @elseif($category === 'live')
                    No one is live right now. Check back later!
                @elseif($category === 'saved')
                    You haven't saved any videos yet.
                @else
                    Be the first to share a video!
                @endif
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($videos as $video)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden group">
                    <!-- Video Thumbnail -->
                    <div class="relative aspect-video bg-gray-900">
                        @if($video->link_url && (str_contains($video->link_url, 'youtube') || str_contains($video->link_url, 'youtu.be')))
                            @php
                                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video->link_url, $matches);
                                $videoId = $matches[1] ?? null;
                            @endphp
                            @if($videoId)
                                <a href="{{ $video->link_url }}" target="_blank" class="block">
                                    <img src="https://img.youtube.com/vi/{{ $videoId }}/maxresdefault.jpg" alt="{{ $video->body }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 group-hover:opacity-100 transition">
                                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z" />
                                        </svg>
                                    </div>
                                </a>
                            @endif
                        @elseif($video->media && count($video->media) > 0)
                            <video class="w-full h-full object-cover" poster="{{ Storage::url($video->media[0]) }}">
                                <source src="{{ Storage::url($video->media[0]) }}" type="video/mp4">
                            </video>
                            <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 group-hover:opacity-100 transition">
                                <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
                                </svg>
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600">
                                <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Video Info -->
                    <div class="p-4">
                        <div class="flex items-start space-x-3">
                            <a href="{{ route('profile.view', $video->user->profile?->username ?? $video->user->id) }}">
                                <img src="{{ $video->user->profile_photo_url }}" alt="{{ $video->user->name }}" class="w-10 h-10 rounded-full">
                            </a>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white line-clamp-2">
                                    {{ $video->body ?: 'Untitled video' }}
                                </p>
                                <a href="{{ route('profile.view', $video->user->profile?->username ?? $video->user->id) }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">
                                    {{ $video->user->name }}
                                </a>
                                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <span>{{ number_format($video->reactions_count) }} reactions</span>
                                    <span class="mx-1">&middot;</span>
                                    <span>{{ $video->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $videos->links() }}
        </div>
    @endif
</div>
