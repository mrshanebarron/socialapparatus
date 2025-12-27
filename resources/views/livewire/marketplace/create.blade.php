<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('marketplace.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Marketplace
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Create a Listing</h1>
            <p class="text-gray-600 dark:text-gray-400">List an item for sale in your community</p>
        </div>

        <form wire:submit="createListing" class="p-6 space-y-6">
            <!-- Images -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Photos</label>
                <div class="grid grid-cols-4 gap-4">
                    @foreach($images as $index => $image)
                        <div class="relative aspect-square">
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover rounded-lg">
                            <button type="button" wire:click="removeImage({{ $index }})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                    @if(count($images) < 10)
                        <label class="aspect-square border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg flex flex-col items-center justify-center cursor-pointer hover:border-indigo-500 dark:hover:border-indigo-400">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="text-xs text-gray-500 mt-1">Add Photo</span>
                            <input type="file" wire:model="images" multiple accept="image/*" class="hidden">
                        </label>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-2">Add up to 10 photos. First photo will be the cover.</p>
                @error('images.*') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                <input type="text" wire:model="title" placeholder="What are you selling?"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Price -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                    <input type="number" wire:model="price" step="0.01" min="0" placeholder="0.00"
                           class="w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <label class="flex items-center mt-2 text-sm text-gray-600 dark:text-gray-400">
                    <input type="checkbox" wire:model="isNegotiable" class="rounded border-gray-300 dark:border-gray-600 mr-2">
                    Price is negotiable
                </label>
                @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                <select wire:model="categoryId" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->icon }} {{ $category->name }}</option>
                        @foreach($category->children as $child)
                            <option value="{{ $child->id }}">&nbsp;&nbsp;&nbsp;{{ $child->icon }} {{ $child->name }}</option>
                        @endforeach
                    @endforeach
                </select>
                @error('categoryId') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Condition -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Condition</label>
                <div class="grid grid-cols-5 gap-2">
                    @foreach($conditions as $key => $label)
                        <button type="button" wire:click="$set('condition', '{{ $key }}')"
                                class="px-3 py-2 text-sm rounded-lg border {{ $condition === $key ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                <textarea wire:model="description" rows="5" placeholder="Describe your item..."
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Location -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location (Optional)</label>
                <input type="text" wire:model="locationName" placeholder="City, State"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            <!-- Shipping -->
            <div>
                <label class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    <input type="checkbox" wire:model="isShippingAvailable" class="rounded border-gray-300 dark:border-gray-600 mr-2">
                    Shipping available
                </label>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('marketplace.index') }}" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="createListing">Publish Listing</span>
                    <span wire:loading wire:target="createListing">Publishing...</span>
                </button>
            </div>
        </form>
    </div>
</div>
