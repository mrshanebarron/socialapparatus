<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Watch Parties</h1>
        <a href="{{ route('watch-parties.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            Create Party
        </a>
    </div>

    <!-- Filters -->
    <div class="flex space-x-2 mb-6">
        @foreach(['live' => 'Live Now', 'upcoming' => 'Upcoming', 'mine' => 'My Parties'] as $key => $label)
            <button wire:click="setFilter('{{ $key }}')" class="px-4 py-2 rounded-lg {{ $filter === $key ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- Parties Grid -->
    @if($parties->isEmpty())
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No watch parties</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create one to watch videos with friends!</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($parties as $party)
                <a href="{{ route('watch-parties.show', $party) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                    <div class="relative aspect-video bg-gray-900">
                        @if($party->video_thumbnail)
                            <img src="{{ $party->video_thumbnail }}" alt="" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        @endif
                        @if($party->status === 'live')
                            <span class="absolute top-2 left-2 bg-red-600 text-white text-xs px-2 py-1 rounded-full animate-pulse">LIVE</span>
                        @endif
                        <span class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                            {{ $party->participant_count }} watching
                        </span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white truncate">{{ $party->title }}</h3>
                        <div class="flex items-center mt-2">
                            <img src="{{ $party->host->profile_photo_url }}" alt="" class="w-6 h-6 rounded-full">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $party->host->name }}</span>
                        </div>
                        @if($party->scheduled_at && $party->status === 'scheduled')
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ $party->scheduled_at->format('M j, Y g:i A') }}
                            </p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $parties->links() }}
        </div>
    @endif
</div>
