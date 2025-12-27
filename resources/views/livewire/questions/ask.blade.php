<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    @if($submitted)
        <div class="text-center py-4">
            <svg class="mx-auto h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Question Sent!</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $recipient->profile?->display_name ?? $recipient->name }} will be notified of your question.
            </p>
        </div>
    @else
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ask {{ $recipient->profile?->display_name ?? $recipient->name }} a Question</h3>

        @if(session()->has('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit="submit" class="space-y-4">
            <div>
                <textarea wire:model="question" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ask anything..."></textarea>
                @error('question') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ 500 - strlen($question) }} characters remaining</p>
            </div>

            @if($allowAnonymous)
                <div class="flex items-center">
                    <input wire:model="isAnonymous" type="checkbox" id="anonymous" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <label for="anonymous" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        Ask anonymously
                    </label>
                </div>
            @endif

            <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                Send Question
            </button>
        </form>
    @endif
</div>
