<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-surface-900 dark:text-surface-100">My Media</h1>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <button
                wire:click="$dispatch('openCreateAlbumModal')"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-surface-700 border border-surface-300 dark:border-surface-600 rounded-xl font-medium text-sm text-surface-700 dark:text-surface-200 hover:bg-surface-50 dark:hover:bg-surface-600 transition-colors"
            >
                <x-heroicon-o-folder-plus class="h-4 w-4 mr-2" />
                New Album
            </button>
            <button
                wire:click="$dispatch('openUploadModal')"
                class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-xl font-medium text-sm text-white hover:bg-primary-700 transition-colors"
            >
                <x-heroicon-o-photo class="h-4 w-4 mr-2" />
                Upload
            </button>
        </div>
    </div>

    <!-- Filter Tabs -->
    <x-ui.card padding="none">
        <nav class="flex" aria-label="Tabs">
            <button
                wire:click="setFilter('photos')"
                class="flex-1 py-4 px-4 text-center font-medium text-sm transition-all duration-200 relative {{ $filter === 'photos' ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-300' }}"
            >
                <span class="flex items-center justify-center gap-2">
                    <x-heroicon-o-photo class="h-5 w-5" />
                    Photos
                </span>
                @if($filter === 'photos')
                    <span class="absolute bottom-0 inset-x-4 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full"></span>
                @endif
            </button>
            <button
                wire:click="setFilter('videos')"
                class="flex-1 py-4 px-4 text-center font-medium text-sm transition-all duration-200 relative {{ $filter === 'videos' ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-300' }}"
            >
                <span class="flex items-center justify-center gap-2">
                    <x-heroicon-o-video-camera class="h-5 w-5" />
                    Videos
                </span>
                @if($filter === 'videos')
                    <span class="absolute bottom-0 inset-x-4 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full"></span>
                @endif
            </button>
            <button
                wire:click="setFilter('albums')"
                class="flex-1 py-4 px-4 text-center font-medium text-sm transition-all duration-200 relative {{ $filter === 'albums' ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-300' }}"
            >
                <span class="flex items-center justify-center gap-2">
                    <x-heroicon-o-folder class="h-5 w-5" />
                    Albums
                </span>
                @if($filter === 'albums')
                    <span class="absolute bottom-0 inset-x-4 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full"></span>
                @endif
            </button>
        </nav>
    </x-ui.card>

    <!-- Content Grid -->
    @if ($viewType === 'media')
        @if ($items->isEmpty())
            <x-ui.card>
                <x-ui.empty-state
                    icon="photo"
                    title="No {{ $filter }} yet"
                    description="Get started by uploading your first {{ Str::singular($filter) }}."
                >
                    <button
                        wire:click="$dispatch('openUploadModal')"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-xl font-medium text-sm text-white hover:bg-primary-700 transition-colors"
                    >
                        Upload {{ Str::ucfirst(Str::singular($filter)) }}
                    </button>
                </x-ui.empty-state>
            </x-ui.card>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach ($items as $item)
                    <div
                        wire:click="$dispatch('openMediaViewer', { mediaId: {{ $item->id }} })"
                        class="relative aspect-square bg-surface-100 dark:bg-surface-700 rounded-xl overflow-hidden cursor-pointer group"
                    >
                        @if ($item->isImage())
                            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->original_filename }}" class="w-full h-full object-cover group-hover:opacity-90 transition">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-surface-800">
                                <x-heroicon-o-play-circle class="h-12 w-12 text-white" />
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition flex items-center justify-center">
                            <x-heroicon-o-magnifying-glass-plus class="h-8 w-8 text-white opacity-0 group-hover:opacity-100 transition" />
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        @if ($items->isEmpty())
            <x-ui.card>
                <x-ui.empty-state
                    icon="folder"
                    title="No albums yet"
                    description="Create your first album to organize your photos."
                >
                    <button
                        wire:click="$dispatch('openCreateAlbumModal')"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-xl font-medium text-sm text-white hover:bg-primary-700 transition-colors"
                    >
                        Create Album
                    </button>
                </x-ui.empty-state>
            </x-ui.card>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach ($items as $album)
                    <a wire:navigate href="{{ route('media.album', $album) }}" class="block group">
                        <div class="relative aspect-square bg-surface-100 dark:bg-surface-700 rounded-xl overflow-hidden mb-2">
                            @if ($album->cover_photo_url)
                                <img src="{{ $album->cover_photo_url }}" alt="{{ $album->name }}" class="w-full h-full object-cover group-hover:opacity-90 transition">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <x-heroicon-o-photo class="h-16 w-16 text-surface-400" />
                                </div>
                            @endif
                        </div>
                        <h3 class="font-medium text-surface-900 dark:text-surface-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 truncate">{{ $album->name }}</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400">{{ $album->media_count }} {{ Str::plural('item', $album->media_count) }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    @endif

    <!-- Pagination -->
    @if ($items->hasPages())
        <div class="mt-6">
            {{ $items->links() }}
        </div>
    @endif

    <!-- Modals -->
    <livewire:media.upload />
    <livewire:media.create-album />
    <livewire:media.media-viewer />
</div>
