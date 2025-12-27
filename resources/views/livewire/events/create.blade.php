<div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('events.index') }}" wire:navigate class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Events
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Create Event</h1>

        <form wire:submit="createEvent" class="space-y-6">
            <!-- Cover Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cover Image</label>
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center">
                    <input type="file" wire:model="coverImage" accept="image/*" class="hidden" id="cover-image">
                    <label for="cover-image" class="cursor-pointer">
                        @if($coverImage)
                            <img src="{{ $coverImage->temporaryUrl() }}" class="max-h-40 mx-auto rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Click to change</p>
                        @else
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Click to upload cover image (optional)</p>
                        @endif
                    </label>
                </div>
                @error('coverImage') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event Title</label>
                <input type="text" wire:model="title" id="title"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Enter event title">
                @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                <textarea wire:model="description" id="description" rows="4"
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                          placeholder="Describe your event..."></textarea>
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Online Toggle -->
            <div class="flex items-center">
                <input type="checkbox" wire:model.live="isOnline" id="is-online"
                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is-online" class="ml-2 text-sm text-gray-700 dark:text-gray-300">This is an online event</label>
            </div>

            <!-- Location / Online Link -->
            @if($isOnline)
                <div>
                    <label for="online-link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Online Link</label>
                    <input type="url" wire:model="onlineLink" id="online-link"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="https://zoom.us/j/...">
                    @error('onlineLink') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            @else
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                    <input type="text" wire:model="location" id="location"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Enter venue address">
                    @error('location') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            @endif

            <!-- Date & Time -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="starts-at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date & Time</label>
                    <input type="datetime-local" wire:model="startsAt" id="starts-at"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    @error('startsAt') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="ends-at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date & Time (optional)</label>
                    <input type="datetime-local" wire:model="endsAt" id="ends-at"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    @error('endsAt') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Privacy -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Privacy</label>
                <div class="space-y-2">
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer {{ $privacy === 'public' ? 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-300 dark:border-indigo-700' : '' }}">
                        <input type="radio" wire:model="privacy" value="public" class="text-indigo-600 focus:ring-indigo-500">
                        <div class="ml-3">
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">Public</span>
                            <span class="block text-xs text-gray-500 dark:text-gray-400">Anyone can see and join this event</span>
                        </div>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer {{ $privacy === 'friends' ? 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-300 dark:border-indigo-700' : '' }}">
                        <input type="radio" wire:model="privacy" value="friends" class="text-indigo-600 focus:ring-indigo-500">
                        <div class="ml-3">
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">Friends Only</span>
                            <span class="block text-xs text-gray-500 dark:text-gray-400">Only your friends can see this event</span>
                        </div>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer {{ $privacy === 'private' ? 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-300 dark:border-indigo-700' : '' }}">
                        <input type="radio" wire:model="privacy" value="private" class="text-indigo-600 focus:ring-indigo-500">
                        <div class="ml-3">
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">Private</span>
                            <span class="block text-xs text-gray-500 dark:text-gray-400">Only people you invite can see this event</span>
                        </div>
                    </label>
                </div>
                @error('privacy') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('events.index') }}" wire:navigate class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg">
                    Create Event
                </button>
            </div>
        </form>
    </div>
</div>
