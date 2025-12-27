<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('media.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-4">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Media
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $album->name }}</h1>
                    @if ($album->description)
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $album->description }}</p>
                    @endif
                    <p class="mt-1 text-sm text-gray-400">{{ $album->media_count }} {{ Str::plural('item', $album->media_count) }}</p>
                </div>
                @if ($album->user_id === auth()->id())
                    <div class="mt-4 sm:mt-0">
                        <button
                            wire:click="$dispatch('openUploadModal')"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                        >
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Photos
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Media Grid -->
        @if ($media->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No photos in this album</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add photos to this album to see them here.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach ($media as $item)
                    <div
                        wire:click="$dispatch('openMediaViewer', { mediaId: {{ $item->id }} })"
                        class="relative aspect-square bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden cursor-pointer group"
                    >
                        @if ($item->isImage())
                            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->original_filename }}" class="w-full h-full object-cover group-hover:opacity-90 transition">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-800">
                                <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        @endif

                        @if ($album->user_id === auth()->id())
                            <button
                                wire:click.stop="deleteMedia({{ $item->id }})"
                                wire:confirm="Are you sure you want to delete this?"
                                class="absolute top-2 right-2 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition hover:bg-red-600"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Pagination -->
        @if ($media->hasPages())
            <div class="mt-6">
                {{ $media->links() }}
            </div>
        @endif
    </div>

    <!-- Modals -->
    <livewire:media.upload :album-id="$album->id" />
    <livewire:media.media-viewer />
</div>
