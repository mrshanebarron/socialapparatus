<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Collection Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
            @if($collection->cover_image)
                <img src="{{ Storage::url($collection->cover_image) }}" alt="" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gradient-to-br from-indigo-500 to-purple-600"></div>
            @endif
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $collection->name }}</h1>
                        @if($collection->description)
                            <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $collection->description }}</p>
                        @endif
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">
                            {{ $collection->items_count }} {{ Str::plural('item', $collection->items_count) }}
                            &bull;
                            Created {{ $collection->created_at->diffForHumans() }}
                        </p>
                    </div>
                    @if($isOwner)
                        <div class="flex space-x-2">
                            <button wire:click="openEditModal" class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                                Edit
                            </button>
                            @unless($collection->is_default)
                                <button wire:click="deleteCollection" wire:confirm="Are you sure you want to delete this collection?" class="inline-flex items-center px-3 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-sm rounded-md hover:bg-red-200 dark:hover:bg-red-900/50">
                                    Delete
                                </button>
                            @endunless
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Collection Items -->
        @if($items->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No items yet</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Save posts, media, and other content to this collection.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($items as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                        <div class="p-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    @if($item->collectable)
                                        <p class="text-sm text-gray-900 dark:text-white">
                                            {{ class_basename($item->collectable_type) }}
                                        </p>
                                        @if($item->note)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $item->note }}</p>
                                        @endif
                                    @else
                                        <p class="text-sm text-gray-500 dark:text-gray-400 italic">Item no longer available</p>
                                    @endif
                                </div>
                                @if($isOwner)
                                    <button wire:click="removeItem({{ $item->id }})" class="text-red-500 hover:text-red-700">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                Added {{ $item->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <!-- Edit Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Edit Collection</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input wire:model="editName" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea wire:model="editDescription" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Privacy</label>
                            <select wire:model="editPrivacy" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="public">Public</option>
                                <option value="friends">Friends Only</option>
                                <option value="private">Private</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button wire:click="$set('showEditModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-500">
                            Cancel
                        </button>
                        <button wire:click="updateCollection" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
