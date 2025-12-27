<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('blog.index') }}" class="mr-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Write Article</h1>
            </div>
        </div>

        <form class="space-y-6">
            <!-- Title -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <input
                    type="text"
                    wire:model="title"
                    placeholder="Article title..."
                    class="w-full text-3xl font-bold border-0 focus:ring-0 bg-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400"
                >
                @error('title') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Featured Image -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Featured Image</label>
                @if ($featuredImage)
                    <div class="relative mb-4">
                        <img src="{{ $featuredImage->temporaryUrl() }}" class="w-full h-48 object-cover rounded-lg">
                        <button type="button" wire:click="$set('featuredImage', null)" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @else
                    <label class="flex justify-center w-full h-32 px-4 transition bg-gray-50 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md appearance-none cursor-pointer hover:border-gray-400 focus:outline-none">
                        <span class="flex flex-col items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="mt-2 text-sm text-gray-500 dark:text-gray-400">Click to upload featured image</span>
                        </span>
                        <input type="file" wire:model="featuredImage" accept="image/*" class="hidden">
                    </label>
                @endif
                @error('featuredImage') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Excerpt -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Excerpt (optional)</label>
                <textarea
                    wire:model="excerpt"
                    rows="2"
                    placeholder="Write a short summary of your article..."
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                ></textarea>
                @error('excerpt') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Body -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Content</label>
                <textarea
                    wire:model="body"
                    rows="20"
                    placeholder="Write your article content here..."
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono"
                ></textarea>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Supports Markdown formatting</p>
                @error('body') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Settings -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Visibility</label>
                        <select wire:model="visibility" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="public">Public - Anyone can read</option>
                            <option value="friends">Friends Only</option>
                            <option value="private">Private - Only me</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" wire:model="commentsEnabled" id="commentsEnabled" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <label for="commentsEnabled" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable comments</label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4">
                <button
                    type="button"
                    wire:click="saveDraft"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-600"
                >
                    Save Draft
                </button>
                <button
                    type="button"
                    wire:click="publish"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                >
                    Publish Now
                </button>
            </div>
        </form>
    </div>
</div>
