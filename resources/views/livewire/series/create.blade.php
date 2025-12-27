<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Create Series</h1>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form wire:submit.prevent="create" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                <input type="text" wire:model="title" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="My Awesome Series">
                @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                <textarea wire:model="description" rows="4" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="What's this series about?"></textarea>
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach(['playlist' => 'Playlist', 'course' => 'Course', 'documentary' => 'Documentary', 'tutorial' => 'Tutorial'] as $value => $label)
                        <label class="relative cursor-pointer">
                            <input type="radio" wire:model="type" value="{{ $value }}" class="sr-only peer">
                            <div class="border-2 rounded-lg p-3 text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $label }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cover Image</label>
                <input type="file" wire:model="coverImage" accept="image/*" class="w-full">
                @if($coverImage)
                    <img src="{{ $coverImage->temporaryUrl() }}" class="mt-2 h-32 rounded-lg object-cover">
                @endif
                @error('coverImage') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Visibility</label>
                <select wire:model="visibility" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <option value="public">Public - Anyone can view</option>
                    <option value="unlisted">Unlisted - Only with link</option>
                    <option value="private">Private - Only you</option>
                </select>
            </div>

            <div class="flex space-x-4">
                <a href="{{ route('series.index') }}" class="flex-1 py-2 text-center border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </a>
                <button type="submit" class="flex-1 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Create Series
                </button>
            </div>
        </form>
    </div>
</div>
