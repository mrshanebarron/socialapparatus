<div>
    <div class="text-center">
        <h3 class="text-lg font-medium text-white">Site Settings</h3>
        <p class="mt-2 text-sm text-gray-400">
            Configure your community's basic settings.
        </p>
    </div>

    <form wire:submit="finishInstallation" class="mt-8 space-y-6">
        <div>
            <label for="app_name" class="block text-sm font-medium text-gray-300">Site Name</label>
            <input type="text" wire:model="app_name" id="app_name" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="My Community">
            @error('app_name') <span class="mt-1 text-sm text-red-400">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="app_url" class="block text-sm font-medium text-gray-300">Site URL</label>
            <input type="url" wire:model="app_url" id="app_url" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="https://example.com">
            @error('app_url') <span class="mt-1 text-sm text-red-400">{{ $message }}</span> @enderror
            <p class="mt-1 text-xs text-gray-400">The full URL where your site will be accessible.</p>
        </div>

        <div>
            <label for="app_description" class="block text-sm font-medium text-gray-300">Site Description (optional)</label>
            <textarea wire:model="app_description" id="app_description" rows="3" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="A brief description of your community..."></textarea>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('install.admin') }}" class="inline-flex items-center px-4 py-2 border border-gray-600 text-sm font-medium rounded-md text-gray-300 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-800">
                <svg class="mr-2 -ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>

            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed" {{ $saving ? 'disabled' : '' }}>
                @if($saving)
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Finishing...
                @else
                    Finish Installation
                    <svg class="ml-2 -mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                @endif
            </button>
        </div>
    </form>
</div>
