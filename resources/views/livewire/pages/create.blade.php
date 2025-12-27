<div class="max-w-2xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create a Page</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Create a page for your business, brand, or community</p>
    </div>

    <form wire:submit="createPage" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Page Name</label>
            <input type="text" id="name" wire:model="name"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Category -->
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
            <select id="category" wire:model="category"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Select a category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
            @error('category') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
            <textarea id="description" wire:model="description" rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Tell people about your page..."></textarea>
            @error('description') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Avatar -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Picture</label>
            <div class="mt-1 flex items-center space-x-4">
                @if($avatar)
                    <img src="{{ $avatar->temporaryUrl() }}" class="h-16 w-16 rounded-lg object-cover">
                @else
                    <div class="h-16 w-16 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                @endif
                <label class="cursor-pointer px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <input type="file" wire:model="avatar" accept="image/*" class="hidden">
                    Choose Image
                </label>
            </div>
            @error('avatar') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Cover Image -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cover Image</label>
            <div class="mt-1">
                @if($coverImage)
                    <img src="{{ $coverImage->temporaryUrl() }}" class="w-full h-32 rounded-lg object-cover mb-2">
                @endif
                <label class="cursor-pointer px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 inline-block">
                    <input type="file" wire:model="coverImage" accept="image/*" class="hidden">
                    {{ $coverImage ? 'Change Cover' : 'Choose Cover' }}
                </label>
            </div>
            @error('coverImage') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <hr class="border-gray-200 dark:border-gray-700">

        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Contact Information (Optional)</h3>

        <!-- Website -->
        <div>
            <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website</label>
            <input type="url" id="website" wire:model="website" placeholder="https://example.com"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('website') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input type="email" id="email" wire:model="email" placeholder="contact@example.com"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
            <input type="tel" id="phone" wire:model="phone" placeholder="+1 (555) 123-4567"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('phone') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Address -->
        <div>
            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
            <input type="text" id="address" wire:model="address" placeholder="123 Main St, City, State"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('address') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Submit -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('pages.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="createPage">Create Page</span>
                <span wire:loading wire:target="createPage">Creating...</span>
            </button>
        </div>
    </form>
</div>
