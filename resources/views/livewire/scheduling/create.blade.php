<div class="max-w-2xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Schedule a Post</h1>

    <form wire:submit="schedule" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Post Content</label>
            <textarea wire:model="content" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="What's on your mind?"></textarea>
            @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Media (Optional)</label>
            <input type="file" wire:model="media" multiple accept="image/*,video/*" class="mt-1 block w-full text-gray-700 dark:text-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Privacy</label>
            <select wire:model="privacy" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="public">Public</option>
                <option value="friends">Friends Only</option>
                <option value="private">Only Me</option>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                <input type="date" wire:model="scheduled_date" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('scheduled_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Time</label>
                <input type="time" wire:model="scheduled_time" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('scheduled_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        @if(count($suggestedTimes) > 0)
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Suggested Optimal Times</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($suggestedTimes as $index => $suggestion)
                        <button type="button" wire:click="useSuggestedTime({{ $index }})" class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full text-sm hover:bg-indigo-200 dark:hover:bg-indigo-800 transition">
                            {{ $suggestion['day'] }} {{ $suggestion['time'] }}
                            <span class="text-xs opacity-75">({{ $suggestion['score'] }}% engagement)</span>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
            Schedule Post
        </button>
    </form>
</div>
