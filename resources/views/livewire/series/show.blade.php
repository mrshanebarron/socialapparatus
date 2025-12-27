<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
        <div class="relative">
            @if($series->cover_image)
                <img src="{{ Storage::url($series->cover_image) }}" alt="{{ $series->title }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gradient-to-br from-purple-500 to-pink-600"></div>
            @endif
        </div>
        <div class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <span class="inline-block px-2 py-1 text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded capitalize mb-2">
                        {{ $series->type }}
                    </span>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $series->title }}</h1>
                    @if($series->description)
                        <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $series->description }}</p>
                    @endif
                </div>
                <div class="flex items-center space-x-3">
                    @if($isOwner)
                        <button wire:click="openAddItemModal" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                            + Add Content
                        </button>
                    @else
                        <button wire:click="toggleFollow" class="px-4 py-2 {{ $isFollowing ? 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' : 'bg-indigo-600 text-white' }} rounded-lg hover:opacity-90 text-sm">
                            {{ $isFollowing ? 'Following' : 'Follow' }}
                        </button>
                    @endif
                </div>
            </div>

            <div class="flex items-center space-x-6 mt-4 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('profile.view', $series->user) }}" class="flex items-center space-x-2 hover:text-indigo-600">
                    <img src="{{ $series->user->profile_photo_url }}" class="w-8 h-8 rounded-full">
                    <span>{{ $series->user->name }}</span>
                </a>
                <span>{{ $series->items->count() }} items</span>
                <span>{{ $series->followers()->count() }} followers</span>
            </div>

            <!-- Progress Bar (for non-owners) -->
            @if(!$isOwner && $userProgress)
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                        <span>Progress</span>
                        <span>{{ $completedCount }}/{{ $series->items->count() }} completed</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all" style="width: {{ $progressPercent }}%"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Content Items -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-4 border-b dark:border-gray-700">
            <h2 class="font-semibold text-gray-900 dark:text-white">Content</h2>
        </div>

        @if($series->items->isEmpty())
            <div class="p-12 text-center">
                <p class="text-gray-500 dark:text-gray-400">No content in this series yet.</p>
                @if($isOwner)
                    <button wire:click="openAddItemModal" class="mt-4 px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Add Content
                    </button>
                @endif
            </div>
        @else
            <div class="divide-y dark:divide-gray-700">
                @foreach($series->items->sortBy('position') as $index => $item)
                    @php
                        $isCompleted = $userProgress && in_array($item->id, $userProgress->completed_items ?? []);
                    @endphp
                    <div class="p-4 flex items-center space-x-4 {{ $isCompleted ? 'bg-green-50 dark:bg-green-900/10' : '' }}">
                        <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full {{ $isCompleted ? 'bg-green-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                            @if($isCompleted)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            @if($item->content)
                                <p class="font-medium text-gray-900 dark:text-white truncate">
                                    {{ $item->content->title ?? $item->content->content ?? 'Untitled' }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 capitalize">
                                    {{ class_basename($item->content_type) }}
                                </p>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 italic">Content unavailable</p>
                            @endif
                        </div>

                        <div class="flex items-center space-x-2">
                            @if(!$isOwner)
                                @if($isCompleted)
                                    <button wire:click="markIncomplete({{ $item->id }})" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                        Mark incomplete
                                    </button>
                                @else
                                    <button wire:click="markComplete({{ $item->id }})" class="text-sm text-indigo-600 hover:text-indigo-800">
                                        Mark complete
                                    </button>
                                @endif
                            @endif
                            @if($isOwner)
                                <button wire:click="removeItem({{ $item->id }})" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Add Item Modal -->
    @if($showAddItemModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeAddItemModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Add Content</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content Type</label>
                        <select wire:model="contentType" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            <option value="post">Post</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" wire:model.live.debounce.300ms="contentSearch" placeholder="Search your content..."
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    </div>

                    @if($availableContent->isNotEmpty())
                        <div class="max-h-60 overflow-y-auto space-y-2">
                            @foreach($availableContent as $content)
                                <button wire:click="addItem('{{ get_class($content) }}', {{ $content->id }})"
                                        class="w-full text-left p-3 border dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <p class="text-sm text-gray-900 dark:text-white truncate">
                                        {{ Str::limit($content->content, 60) }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $content->created_at->format('M d, Y') }}
                                    </p>
                                </button>
                            @endforeach
                        </div>
                    @elseif($contentSearch)
                        <p class="text-center text-gray-500 dark:text-gray-400 py-4">No content found.</p>
                    @endif

                    <button wire:click="closeAddItemModal" class="w-full py-2 text-gray-600 dark:text-gray-400">Cancel</button>
                </div>
            </div>
        </div>
    @endif
</div>
