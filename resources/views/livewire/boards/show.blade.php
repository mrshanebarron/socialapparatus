<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $board->name }}</h1>
                    @if($board->description)
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $board->description }}</p>
                    @endif
                </div>
                <div class="flex items-center space-x-3">
                    @if($canEdit)
                        <button wire:click="openAddColumnModal" class="px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                            + Add Column
                        </button>
                    @endif
                    <span class="text-xs px-2 py-1 rounded-full {{ $board->visibility === 'public' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ ucfirst($board->visibility) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="p-6 overflow-x-auto">
        <div class="flex space-x-4 min-w-max" x-data="{ dragging: null }">
            @foreach($board->columns as $column)
                <div class="w-80 bg-gray-200 dark:bg-gray-700 rounded-lg p-3 flex-shrink-0">
                    <!-- Column Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $column->color }}"></div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $column->name }}</h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400">({{ $column->items->count() }})</span>
                        </div>
                        @if($isOwner)
                            <button wire:click="deleteColumn({{ $column->id }})" class="text-gray-400 hover:text-red-500" onclick="return confirm('Delete this column and all its items?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>

                    <!-- Items -->
                    <div class="space-y-2 min-h-[200px]">
                        @foreach($column->items as $item)
                            <div wire:click="openItemDetail({{ $item->id }})"
                                 class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow cursor-pointer hover:shadow-md transition">
                                @if($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="" class="w-full h-24 object-cover rounded mb-2">
                                @endif
                                <div class="flex items-start justify-between">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white {{ $item->is_completed ? 'line-through opacity-50' : '' }}">
                                        {{ $item->title }}
                                    </h4>
                                    @if($item->priority === 'urgent')
                                        <span class="px-1.5 py-0.5 text-xs bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 rounded">!</span>
                                    @elseif($item->priority === 'high')
                                        <span class="px-1.5 py-0.5 text-xs bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300 rounded">H</span>
                                    @endif
                                </div>
                                @if($item->due_date)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                        Due: {{ \Carbon\Carbon::parse($item->due_date)->format('M d') }}
                                    </p>
                                @endif
                                @if($item->assignees->isNotEmpty())
                                    <div class="flex -space-x-2 mt-2">
                                        @foreach($item->assignees->take(3) as $assignee)
                                            <img src="{{ $assignee->user->profile_photo_url }}" class="w-6 h-6 rounded-full ring-2 ring-white dark:ring-gray-800">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Add Item Button -->
                    @if($canEdit)
                        <button wire:click="openAddItemModal({{ $column->id }})" class="w-full mt-3 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition">
                            + Add item
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Add Item Modal -->
    @if($showAddItemModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeAddItemModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Add Item</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                        <input type="text" wire:model="itemTitle" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        @error('itemTitle') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea wire:model="itemDescription" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Due Date</label>
                            <input type="date" wire:model="itemDueDate" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                            <select wire:model="itemPriority" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Image</label>
                        <input type="file" wire:model="itemImage" accept="image/*" class="w-full">
                    </div>
                    <div class="flex space-x-3">
                        <button wire:click="closeAddItemModal" class="flex-1 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg">Cancel</button>
                        <button wire:click="createItem" class="flex-1 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Add</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Item Detail Modal -->
    @if($showItemDetailModal && $activeItem)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeItemDetail"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-lg w-full max-h-[80vh] overflow-y-auto">
                @if($activeItem->image)
                    <img src="{{ Storage::url($activeItem->image) }}" alt="" class="w-full h-48 object-cover rounded-t-lg">
                @endif
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white {{ $activeItem->is_completed ? 'line-through' : '' }}">
                            {{ $activeItem->title }}
                        </h3>
                        <div class="flex items-center space-x-2">
                            @if($canEdit)
                                <button wire:click="toggleItemComplete({{ $activeItem->id }})" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                    @if($activeItem->is_completed)
                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </button>
                                <button wire:click="deleteItem({{ $activeItem->id }})" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg" onclick="return confirm('Delete this item?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            @endif
                            <button wire:click="closeItemDetail" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    @if($activeItem->description)
                        <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $activeItem->description }}</p>
                    @endif

                    <div class="flex flex-wrap gap-3 text-sm text-gray-500 dark:text-gray-400">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $activeItem->user->name }}
                        </span>
                        @if($activeItem->due_date)
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($activeItem->due_date)->format('M d, Y') }}
                            </span>
                        @endif
                        <span class="px-2 py-0.5 rounded-full text-xs
                            {{ $activeItem->priority === 'urgent' ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' : '' }}
                            {{ $activeItem->priority === 'high' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300' : '' }}
                            {{ $activeItem->priority === 'medium' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' : '' }}
                            {{ $activeItem->priority === 'low' ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' : '' }}
                        ">
                            {{ ucfirst($activeItem->priority) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Add Column Modal -->
    @if($showAddColumnModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeAddColumnModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-sm w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Add Column</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                        <input type="text" wire:model="columnName" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Color</label>
                        <input type="color" wire:model="columnColor" class="w-full h-10 rounded-lg cursor-pointer">
                    </div>
                    <div class="flex space-x-3">
                        <button wire:click="closeAddColumnModal" class="flex-1 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg">Cancel</button>
                        <button wire:click="createColumn" class="flex-1 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Add</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
