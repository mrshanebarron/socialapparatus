<div class="max-w-2xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Request Verification</h1>

    @if($existingRequest)
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-blue-800 dark:text-blue-200">Request Pending</h2>
            <p class="mt-2 text-blue-700 dark:text-blue-300">You already have a verification request under review.</p>
            <a href="{{ route('verification.status') }}" class="mt-3 inline-block text-blue-600 dark:text-blue-400 hover:underline">View Status</a>
        </div>
    @else
        <form wire:submit="submit" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Verification Type</label>
                <select wire:model="type" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="identity">Personal Identity</option>
                    <option value="creator">Content Creator</option>
                    <option value="business">Business</option>
                    <option value="organization">Organization</option>
                    <option value="government">Government Official</option>
                    <option value="notable">Notable Figure</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Legal Name</label>
                <input type="text" wire:model="full_legal_name" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('full_legal_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Known As (Stage name, brand, etc.)</label>
                <input type="text" wire:model="known_as" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                <input type="text" wire:model="category" placeholder="e.g., Musician, Actor, Journalist, CEO" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Why should you be verified?</label>
                <textarea wire:model="description" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Explain your public presence, achievements, and why verification is important..."></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Identity Documents</label>
                <input type="file" wire:model="documents" multiple accept=".jpg,.jpeg,.png,.pdf" class="mt-1 block w-full text-gray-700 dark:text-gray-300">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload government ID, business license, or other proof. JPG, PNG, PDF up to 10MB each.</p>
                @error('documents') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @error('documents.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">External Links (Optional)</label>
                <div class="flex space-x-2">
                    <input type="url" wire:model="newLink" placeholder="https://..." class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="button" wire:click="addLink" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition">Add</button>
                </div>
                @if(count($links) > 0)
                    <ul class="mt-2 space-y-1">
                        @foreach($links as $index => $link)
                            <li class="flex items-center justify-between text-sm bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded">
                                <span class="truncate">{{ $link }}</span>
                                <button type="button" wire:click="removeLink({{ $index }})" class="text-red-500 hover:text-red-600 ml-2">&times;</button>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
                Submit Request
            </button>
        </form>
    @endif
</div>
