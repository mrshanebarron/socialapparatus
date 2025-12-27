<div class="max-w-2xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Create Soundbite</h1>

    <form wire:submit="create" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Audio File</label>
            <input type="file" wire:model="audio" accept="audio/*" class="mt-1 block w-full text-gray-700 dark:text-gray-300">
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">MP3, WAV, M4A, OGG up to 50MB</p>
            @error('audio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            @if($audio)
                <div class="mt-2">
                    <audio controls class="w-full">
                        <source src="{{ $audio->temporaryUrl() }}" type="{{ $audio->getMimeType() }}">
                    </audio>
                </div>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cover Image (Optional)</label>
            <input type="file" wire:model="cover" accept="image/*" class="mt-1 block w-full text-gray-700 dark:text-gray-300">
            @if($cover)
                <img src="{{ $cover->temporaryUrl() }}" alt="Cover preview" class="mt-2 w-32 h-32 object-cover rounded-lg">
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title (Optional)</label>
            <input type="text" wire:model="title" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description (Optional)</label>
            <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
            <select wire:model="category_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Select category...</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Privacy</label>
            <select wire:model="privacy" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="public">Public</option>
                <option value="friends">Friends Only</option>
                <option value="private">Private</option>
            </select>
        </div>

        <div class="flex items-center space-x-6">
            <label class="flex items-center">
                <input type="checkbox" wire:model="allow_comments" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Allow comments</span>
            </label>
            <label class="flex items-center">
                <input type="checkbox" wire:model="allow_duets" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Allow duets</span>
            </label>
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
            Create Soundbite
        </button>
    </form>
</div>
