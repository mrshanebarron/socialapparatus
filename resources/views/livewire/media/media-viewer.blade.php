<div>
    @if ($showViewer && $media)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-90" x-data x-transition>
            <!-- Close button -->
            <button
                wire:click="closeViewer"
                class="absolute top-4 right-4 text-white hover:text-gray-300 z-10"
            >
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="min-h-screen flex items-center justify-center p-4">
                <div class="max-w-6xl w-full flex flex-col lg:flex-row gap-4">
                    <!-- Media Display -->
                    <div class="flex-1 flex items-center justify-center">
                        @if ($media->isImage())
                            <img src="{{ $media->url }}" alt="{{ $media->original_filename }}" class="max-w-full max-h-[80vh] object-contain">
                        @elseif ($media->isVideo())
                            <video controls class="max-w-full max-h-[80vh]">
                                <source src="{{ $media->url }}" type="{{ $media->mime_type }}">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    </div>

                    <!-- Sidebar Info -->
                    <div class="lg:w-80 bg-white dark:bg-gray-800 rounded-lg p-4 max-h-[80vh] overflow-y-auto">
                        <!-- User Info -->
                        <div class="flex items-center space-x-3 mb-4">
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $media->user->profile_photo_url }}" alt="{{ $media->user->name }}">
                            <div>
                                <a href="{{ route('profile.view', $media->user->profile?->username ?? $media->user->id) }}" class="font-medium text-gray-900 dark:text-gray-100 hover:underline">
                                    {{ $media->user->name }}
                                </a>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $media->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <!-- Caption -->
                        @if ($media->caption)
                            <p class="text-gray-800 dark:text-gray-200 mb-4">{{ $media->caption }}</p>
                        @endif

                        <!-- Stats & Actions -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mb-4">
                            <div class="flex items-center justify-between">
                                <button
                                    wire:click="toggleLike"
                                    class="flex items-center space-x-2 {{ $media->isLikedBy(auth()->user()) ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }} hover:text-red-500"
                                >
                                    @if ($media->isLikedBy(auth()->user()))
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    @endif
                                    <span>{{ $media->likes_count }}</span>
                                </button>

                                @if ($media->user_id === auth()->id())
                                    <button
                                        wire:click="deleteMedia"
                                        wire:confirm="Are you sure you want to delete this media?"
                                        class="text-red-500 hover:text-red-700"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Media Details -->
                        <div class="text-sm text-gray-500 dark:text-gray-400 space-y-1">
                            <p><span class="font-medium">Filename:</span> {{ $media->original_filename }}</p>
                            <p><span class="font-medium">Size:</span> {{ $media->size_for_humans }}</p>
                            @if ($media->width && $media->height)
                                <p><span class="font-medium">Dimensions:</span> {{ $media->width }} x {{ $media->height }}</p>
                            @endif
                            <p><span class="font-medium">Type:</span> {{ $media->mime_type }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
