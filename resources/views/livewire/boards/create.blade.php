<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Create Board</h1>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form wire:submit.prevent="create" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Board Name</label>
                <input type="text" wire:model="name" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="My Awesome Board">
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                <textarea wire:model="description" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="What's this board about?"></textarea>
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Board Type</label>
                <div class="grid grid-cols-3 gap-4">
                    <label class="relative cursor-pointer">
                        <input type="radio" wire:model="type" value="kanban" class="sr-only peer">
                        <div class="border-2 rounded-lg p-4 text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-400 peer-checked:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Kanban</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" wire:model="type" value="gallery" class="sr-only peer">
                        <div class="border-2 rounded-lg p-4 text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Gallery</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" wire:model="type" value="list" class="sr-only peer">
                        <div class="border-2 rounded-lg p-4 text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">List</span>
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Visibility</label>
                <select wire:model="visibility" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <option value="private">Private - Only you can see</option>
                    <option value="team">Team - Collaborators only</option>
                    <option value="public">Public - Anyone can see</option>
                </select>
            </div>

            <div class="flex space-x-4">
                <a href="{{ route('boards.index') }}" class="flex-1 py-2 text-center border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </a>
                <button type="submit" class="flex-1 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Create Board
                </button>
            </div>
        </form>
    </div>
</div>
