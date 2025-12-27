<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('fundraisers.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Fundraisers
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Start a Fundraiser</h1>
            <p class="text-gray-600 dark:text-gray-400">Share your story and reach your fundraising goal</p>
        </div>

        <form wire:submit="createFundraiser" class="p-6 space-y-6">
            <!-- Cover Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cover Photo</label>
                @if($coverImage)
                    <div class="relative aspect-video rounded-lg overflow-hidden mb-2">
                        <img src="{{ $coverImage->temporaryUrl() }}" class="w-full h-full object-cover">
                        <button type="button" wire:click="$set('coverImage', null)" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @else
                    <label class="block border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center cursor-pointer hover:border-indigo-500">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-gray-600 dark:text-gray-400">Click to upload cover photo</span>
                        <input type="file" wire:model="coverImage" accept="image/*" class="hidden">
                    </label>
                @endif
                @error('coverImage') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fundraiser Title</label>
                <input type="text" wire:model="title" placeholder="Give your fundraiser a title..."
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Goal Amount -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Goal Amount</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                    <input type="number" wire:model="goalAmount" step="1" min="100" placeholder="1000"
                           class="w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimum goal: $100</p>
                @error('goalAmount') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                <select wire:model="category" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Beneficiary -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Who is this fundraiser for? (Optional)</label>
                <input type="text" wire:model="beneficiaryName" placeholder="e.g., John Doe, Local Animal Shelter"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <p class="text-xs text-gray-500 mt-1">Leave blank if you're raising for yourself</p>
            </div>

            <!-- Story -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your Story</label>
                <textarea wire:model="story" rows="8" placeholder="Tell your story. Why are you raising money? How will the funds be used? Be specific and heartfelt..."
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                <p class="text-xs text-gray-500 mt-1">Minimum 100 characters. Be detailed to inspire donors.</p>
                @error('story') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date (Optional)</label>
                <input type="datetime-local" wire:model="endsAt"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                       min="{{ now()->format('Y-m-d\TH:i') }}">
                <p class="text-xs text-gray-500 mt-1">Leave blank for an ongoing fundraiser</p>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('fundraisers.index') }}" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="createFundraiser">Create Fundraiser</span>
                    <span wire:loading wire:target="createFundraiser">Creating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
