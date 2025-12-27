<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
    <div class="flex items-center space-x-4 overflow-x-auto pb-2">
        <!-- Create Story Button -->
        <button wire:click="openCreateModal" class="flex-shrink-0 flex flex-col items-center">
            <div class="relative">
                @if($hasOwnStory)
                    <img src="{{ auth()->user()->profile_photo_url }}"
                         alt="Your story"
                         class="w-16 h-16 rounded-full ring-2 ring-indigo-500 p-0.5">
                @else
                    <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center ring-2 ring-gray-200 dark:ring-gray-600">
                        <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                @endif
            </div>
            <span class="mt-1 text-xs text-gray-600 dark:text-gray-400 truncate w-16 text-center">
                {{ $hasOwnStory ? 'Your Story' : 'Add Story' }}
            </span>
        </button>

        <!-- Friends' Stories -->
        @foreach($usersWithStories as $storyUser)
            @if($storyUser->id !== auth()->id())
                <button wire:click="viewUserStories({{ $storyUser->id }})" class="flex-shrink-0 flex flex-col items-center">
                    @php
                        $hasUnviewed = $storyUser->stories->contains(fn($s) => !$s->viewers->contains('id', auth()->id()));
                    @endphp
                    <div class="relative">
                        <img src="{{ $storyUser->profile_photo_url }}"
                             alt="{{ $storyUser->name }}"
                             class="w-16 h-16 rounded-full {{ $hasUnviewed ? 'ring-2 ring-indigo-500' : 'ring-2 ring-gray-300 dark:ring-gray-600' }} p-0.5">
                    </div>
                    <span class="mt-1 text-xs text-gray-600 dark:text-gray-400 truncate w-16 text-center">
                        {{ $storyUser->profile?->display_name ?? $storyUser->name }}
                    </span>
                </button>
            @endif
        @endforeach
    </div>

    <!-- Create Story Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data x-init="document.body.classList.add('overflow-hidden')" x-on:destroy.window="document.body.classList.remove('overflow-hidden')">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" wire:click="closeCreateModal"></div>

                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Create Story</h3>
                        <button wire:click="closeCreateModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Story Type Tabs -->
                    <div class="flex space-x-4 mb-4">
                        <button wire:click="$set('storyType', 'text')"
                                class="flex-1 py-2 px-4 rounded-lg {{ $storyType === 'text' ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                            Text
                        </button>
                        <button wire:click="$set('storyType', 'media')"
                                class="flex-1 py-2 px-4 rounded-lg {{ $storyType === 'media' ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                            Photo/Video
                        </button>
                    </div>

                    @if($storyType === 'text')
                        <!-- Text Story -->
                        <div class="space-y-4">
                            <div class="relative rounded-lg overflow-hidden" style="background-color: {{ $backgroundColor }}; min-height: 200px;">
                                <textarea wire:model="textContent"
                                          placeholder="What's on your mind?"
                                          class="w-full h-48 bg-transparent text-white text-xl font-semibold text-center p-4 resize-none border-0 focus:ring-0 placeholder-white/50"
                                          style="vertical-align: middle;"></textarea>
                            </div>

                            <!-- Color Picker -->
                            <div class="flex space-x-2 justify-center">
                                @foreach(['#4F46E5', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#06B6D4', '#1F2937'] as $color)
                                    <button wire:click="$set('backgroundColor', '{{ $color }}')"
                                            class="w-8 h-8 rounded-full {{ $backgroundColor === $color ? 'ring-2 ring-offset-2 ring-gray-900 dark:ring-white' : '' }}"
                                            style="background-color: {{ $color }}"></button>
                                @endforeach
                            </div>

                            <button wire:click="createTextStory" class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium">
                                Share Story
                            </button>
                        </div>
                    @else
                        <!-- Media Story -->
                        <div class="space-y-4">
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center">
                                <input type="file" wire:model="mediaFile" accept="image/*,video/*" class="hidden" id="story-media">
                                <label for="story-media" class="cursor-pointer">
                                    @if($mediaFile)
                                        @if(str_starts_with($mediaFile->getMimeType(), 'image/'))
                                            <img src="{{ $mediaFile->temporaryUrl() }}" class="max-h-48 mx-auto rounded-lg">
                                        @else
                                            <video src="{{ $mediaFile->temporaryUrl() }}" class="max-h-48 mx-auto rounded-lg" controls></video>
                                        @endif
                                    @else
                                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Click to upload photo or video</p>
                                    @endif
                                </label>
                            </div>

                            <button wire:click="createMediaStory"
                                    @if(!$mediaFile) disabled @endif
                                    class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium">
                                Share Story
                            </button>
                        </div>
                    @endif

                    @error('textContent') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    @error('mediaFile') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    @endif

    <!-- Story Viewer Modal -->
    @if($showViewerModal && count($viewingStories) > 0)
        <div class="fixed inset-0 z-50 bg-black" x-data="{ progress: 0 }" x-init="
            let interval = setInterval(() => {
                progress += 2;
                if (progress >= 100) {
                    progress = 0;
                    $wire.nextStory();
                }
            }, 100);
            $watch('$wire.currentStoryIndex', () => progress = 0);
        ">
            <!-- Progress Bars -->
            <div class="absolute top-4 left-4 right-4 flex space-x-1 z-10">
                @foreach($viewingStories as $index => $story)
                    <div class="flex-1 h-1 bg-white/30 rounded-full overflow-hidden">
                        <div class="h-full bg-white transition-all duration-100 ease-linear"
                             style="width: {{ $index < $currentStoryIndex ? '100%' : ($index === $currentStoryIndex ? 'var(--progress)' : '0%') }}"
                             :style="{ '--progress': {{ $index === $currentStoryIndex ? 'progress' : ($index < $currentStoryIndex ? '100' : '0') }} + '%' }"></div>
                    </div>
                @endforeach
            </div>

            <!-- Header -->
            <div class="absolute top-8 left-4 right-4 flex items-center justify-between z-10">
                <div class="flex items-center space-x-3">
                    <img src="{{ $viewingStories[$currentStoryIndex]->user->profile_photo_url }}"
                         class="w-10 h-10 rounded-full ring-2 ring-white">
                    <div>
                        <p class="text-white font-semibold">{{ $viewingStories[$currentStoryIndex]->user->name }}</p>
                        <p class="text-white/70 text-xs">{{ $viewingStories[$currentStoryIndex]->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <button wire:click="closeViewer" class="text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Story Content -->
            <div class="absolute inset-0 flex items-center justify-center">
                @php $currentStory = $viewingStories[$currentStoryIndex] ?? null; @endphp
                @if($currentStory)
                    @if($currentStory->type === 'text')
                        <div class="w-full h-full flex items-center justify-center p-8" style="background-color: {{ $currentStory->background_color }}">
                            <p class="text-white text-2xl font-semibold text-center">{{ $currentStory->text_content }}</p>
                        </div>
                    @elseif($currentStory->type === 'image')
                        <img src="{{ Storage::url($currentStory->media_path) }}" class="max-w-full max-h-full object-contain">
                    @elseif($currentStory->type === 'video')
                        <video src="{{ Storage::url($currentStory->media_path) }}" class="max-w-full max-h-full object-contain" autoplay muted></video>
                    @endif
                @endif
            </div>

            <!-- Navigation Overlays -->
            <button wire:click="prevStory" class="absolute left-0 top-0 bottom-0 w-1/3 z-10" @if($currentStoryIndex === 0) disabled @endif></button>
            <button wire:click="nextStory" class="absolute right-0 top-0 bottom-0 w-1/3 z-10"></button>
        </div>
    @endif
</div>
