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
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Edit Article</h1>
                @if ($article->isDraft())
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Draft
                    </span>
                @else
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Published
                    </span>
                @endif
            </div>
            <button
                type="button"
                wire:click="delete"
                wire:confirm="Are you sure you want to delete this article?"
                class="text-red-600 hover:text-red-800"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>

        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('message') }}
            </div>
        @endif

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
                @elseif ($existingFeaturedImage)
                    <div class="relative mb-4">
                        <img src="{{ asset('storage/' . $existingFeaturedImage) }}" class="w-full h-48 object-cover rounded-lg">
                        <button type="button" wire:click="removeImage" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
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
            <div class="flex items-center justify-between">
                <div>
                    @if ($article->isPublished())
                        <button
                            type="button"
                            wire:click="unpublish"
                            class="text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200"
                        >
                            Unpublish
                        </button>
                    @endif
                </div>
                <div class="flex items-center space-x-4">
                    <button
                        type="button"
                        wire:click="saveDraft"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-600"
                    >
                        Save Changes
                    </button>
                    @if ($article->isDraft())
                        <button
                            type="button"
                            wire:click="publish"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                        >
                            Publish Now
                        </button>
                    @else
                        <button
                            type="button"
                            wire:click="publish"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                        >
                            Update
                        </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
