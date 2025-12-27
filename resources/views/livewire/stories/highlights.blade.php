<div>
    <!-- Highlights Row -->
    <div class="flex items-center space-x-4 overflow-x-auto pb-2">
        <!-- Add New Highlight (Owner Only) -->
        @if($isOwner)
            <button wire:click="openCreateModal" class="flex-shrink-0 flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center ring-2 ring-gray-200 dark:ring-gray-600">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <span class="mt-1 text-xs text-gray-600 dark:text-gray-400 truncate w-16 text-center">New</span>
            </button>
        @endif

        <!-- Existing Highlights -->
        @foreach($highlights as $highlight)
            <div class="flex-shrink-0 flex flex-col items-center relative group">
                <button wire:click="viewHighlight({{ $highlight->id }})" class="relative">
                    @if($highlight->cover_image)
                        <img src="{{ Storage::url($highlight->cover_image) }}" alt="{{ $highlight->title }}" class="w-16 h-16 rounded-full object-cover ring-2 ring-gray-300 dark:ring-gray-600 p-0.5">
                    @elseif($highlight->items->first()?->story?->media_path)
                        <img src="{{ Storage::url($highlight->items->first()->story->media_path) }}" alt="{{ $highlight->title }}" class="w-16 h-16 rounded-full object-cover ring-2 ring-gray-300 dark:ring-gray-600 p-0.5">
                    @else
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 ring-2 ring-gray-300 dark:ring-gray-600 p-0.5 flex items-center justify-center">
                            <span class="text-white text-lg font-bold">{{ substr($highlight->title, 0, 1) }}</span>
                        </div>
                    @endif
                </button>
                <span class="mt-1 text-xs text-gray-600 dark:text-gray-400 truncate w-16 text-center">{{ $highlight->title }}</span>

                @if($isOwner)
                    <div class="absolute -top-1 -right-1 hidden group-hover:flex space-x-1">
                        <button wire:click="openEditModal({{ $highlight->id }})" class="w-5 h-5 bg-white dark:bg-gray-700 rounded-full shadow flex items-center justify-center text-gray-500 hover:text-indigo-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                        </button>
                        <button wire:click="deleteHighlight({{ $highlight->id }})" class="w-5 h-5 bg-white dark:bg-gray-700 rounded-full shadow flex items-center justify-center text-gray-500 hover:text-red-600" onclick="return confirm('Delete this highlight?')">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeCreateModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full max-h-[80vh] overflow-y-auto p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">New Highlight</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                        <input type="text" wire:model="highlightTitle" maxlength="50" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="Highlight name">
                        @error('highlightTitle') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cover Image (optional)</label>
                        <input type="file" wire:model="coverImage" accept="image/*" class="w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Stories</label>
                        @if($archivedStories->isEmpty())
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No archived stories available.</p>
                        @else
                            <div class="grid grid-cols-4 gap-2 max-h-60 overflow-y-auto">
                                @foreach($archivedStories as $story)
                                    <button wire:click="toggleStorySelection({{ $story->id }})" class="relative aspect-square rounded-lg overflow-hidden {{ in_array($story->id, $selectedStoryIds) ? 'ring-2 ring-indigo-500' : '' }}">
                                        @if($story->type === 'text')
                                            <div class="w-full h-full flex items-center justify-center text-xs text-white p-1" style="background-color: {{ $story->background_color }}">
                                                {{ Str::limit($story->text_content, 20) }}
                                            </div>
                                        @else
                                            <img src="{{ Storage::url($story->media_path) }}" class="w-full h-full object-cover">
                                        @endif
                                        @if(in_array($story->id, $selectedStoryIds))
                                            <div class="absolute inset-0 bg-indigo-500/30 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        @endif
                        @error('selectedStoryIds') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex space-x-3">
                        <button wire:click="closeCreateModal" class="flex-1 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg">Cancel</button>
                        <button wire:click="createHighlight" class="flex-1 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Create</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Viewer Modal -->
    @if($showViewerModal && $viewingHighlight)
        <div class="fixed inset-0 z-50 bg-black" x-data="{ progress: 0 }" x-init="
            let interval = setInterval(() => {
                progress += 1;
                if (progress >= 100) {
                    progress = 0;
                    $wire.nextItem();
                }
            }, 50);
        ">
            <!-- Progress Bars -->
            <div class="absolute top-4 left-4 right-4 flex space-x-1 z-10">
                @foreach($viewingHighlight->items as $index => $item)
                    <div class="flex-1 h-1 bg-white/30 rounded-full overflow-hidden">
                        <div class="h-full bg-white transition-all duration-100"
                             style="width: {{ $index < $currentItemIndex ? '100%' : ($index === $currentItemIndex ? 'var(--progress)' : '0%') }}"
                             :style="{ '--progress': {{ $index === $currentItemIndex ? 'progress' : ($index < $currentItemIndex ? '100' : '0') }} + '%' }"></div>
                    </div>
                @endforeach
            </div>

            <!-- Header -->
            <div class="absolute top-8 left-4 right-4 flex items-center justify-between z-10">
                <div class="flex items-center space-x-3">
                    <p class="text-white font-semibold">{{ $viewingHighlight->title }}</p>
                </div>
                <button wire:click="closeViewer" class="text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="absolute inset-0 flex items-center justify-center">
                @if($viewingHighlight->items->isNotEmpty() && isset($viewingHighlight->items[$currentItemIndex]))
                    @php $currentStory = $viewingHighlight->items[$currentItemIndex]->story; @endphp
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
                @endif
            </div>

            <!-- Navigation -->
            <button wire:click="prevItem" class="absolute left-0 top-0 bottom-0 w-1/3 z-10"></button>
            <button wire:click="nextItem" class="absolute right-0 top-0 bottom-0 w-1/3 z-10"></button>
        </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal && $editingHighlight)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeEditModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full max-h-[80vh] overflow-y-auto p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Edit Highlight</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                        <input type="text" wire:model="highlightTitle" maxlength="50" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cover Image</label>
                        <input type="file" wire:model="coverImage" accept="image/*" class="w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stories</label>
                        <div class="grid grid-cols-4 gap-2 max-h-60 overflow-y-auto">
                            @foreach($archivedStories as $story)
                                <button wire:click="toggleStorySelection({{ $story->id }})" class="relative aspect-square rounded-lg overflow-hidden {{ in_array($story->id, $selectedStoryIds) ? 'ring-2 ring-indigo-500' : '' }}">
                                    @if($story->type === 'text')
                                        <div class="w-full h-full flex items-center justify-center text-xs text-white p-1" style="background-color: {{ $story->background_color }}">
                                            {{ Str::limit($story->text_content, 20) }}
                                        </div>
                                    @else
                                        <img src="{{ Storage::url($story->media_path) }}" class="w-full h-full object-cover">
                                    @endif
                                    @if(in_array($story->id, $selectedStoryIds))
                                        <div class="absolute inset-0 bg-indigo-500/30 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <button wire:click="closeEditModal" class="flex-1 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg">Cancel</button>
                        <button wire:click="updateHighlight" class="flex-1 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
