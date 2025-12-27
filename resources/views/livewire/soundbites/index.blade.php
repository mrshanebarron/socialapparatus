<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Soundbites</h1>
        <a href="{{ route('soundbites.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            Create Soundbite
        </a>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach(['featured' => 'Featured', 'recent' => 'Recent', 'popular' => 'Popular'] as $key => $label)
            <button wire:click="setFilter('{{ $key }}')" class="px-4 py-2 rounded-lg {{ $filter === $key ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- Categories -->
    <div class="flex overflow-x-auto space-x-2 pb-4 mb-6">
        <button wire:click="setCategory(null)" class="px-4 py-2 rounded-full whitespace-nowrap {{ !$category ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
            All
        </button>
        @foreach($categories as $cat)
            <button wire:click="setCategory({{ $cat->id }})" class="px-4 py-2 rounded-full whitespace-nowrap {{ $category === $cat->id ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                {{ $cat->icon }} {{ $cat->name }}
            </button>
        @endforeach
    </div>

    <!-- Soundbites Grid -->
    @if($soundbites->isEmpty())
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No soundbites yet</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Be the first to share an audio post!</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($soundbites as $soundbite)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <!-- Cover / Waveform -->
                    <div class="relative aspect-video bg-gradient-to-br from-purple-500 to-pink-500">
                        @if($soundbite->cover_image)
                            <img src="{{ $soundbite->cover_image_url }}" alt="" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-50" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                                </svg>
                            </div>
                        @endif
                        <span class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                            {{ $soundbite->formatted_duration }}
                        </span>
                    </div>

                    <div class="p-4">
                        <!-- User -->
                        <div class="flex items-center space-x-3 mb-3">
                            <img src="{{ $soundbite->user->profile_photo_url }}" alt="" class="w-10 h-10 rounded-full">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $soundbite->user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $soundbite->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        @if($soundbite->title)
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $soundbite->title }}</h3>
                        @endif

                        <!-- Audio Player -->
                        <audio controls class="w-full mb-3">
                            <source src="{{ $soundbite->audio_url }}" type="audio/mpeg">
                        </audio>

                        <!-- Stats -->
                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                            <span>{{ number_format($soundbite->plays_count) }} plays</span>
                            <button wire:click="toggleLike({{ $soundbite->id }})" class="flex items-center space-x-1 hover:text-red-500 transition">
                                <svg class="w-5 h-5 {{ $soundbite->likes()->where('user_id', auth()->id())->exists() ? 'text-red-500 fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span>{{ $soundbite->likes_count }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $soundbites->links() }}
        </div>
    @endif
</div>
