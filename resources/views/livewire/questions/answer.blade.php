<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Answer Question</h2>

                <!-- Question -->
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg mb-6">
                    <div class="flex items-center space-x-2 mb-2">
                        @if($question->is_anonymous)
                            <span class="text-sm text-gray-500 dark:text-gray-400">Anonymous asked</span>
                        @elseif($question->asker)
                            <img class="h-6 w-6 rounded-full object-cover" src="{{ $question->asker->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($question->asker->name) }}" alt="">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $question->asker->profile?->display_name ?? $question->asker->name }}
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">asked</span>
                        @endif
                        <span class="text-xs text-gray-400">{{ $question->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-lg text-gray-900 dark:text-white">{{ $question->question }}</p>
                </div>

                <form wire:submit="submit" class="space-y-4">
                    <div>
                        <label for="answer" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Your Answer</label>
                        <textarea wire:model="answer" id="answer" rows="5" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Type your answer..."></textarea>
                        @error('answer') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center">
                        <input wire:model="isPublic" type="checkbox" id="is_public" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <label for="is_public" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            Show this Q&A on my profile
                        </label>
                    </div>

                    <div class="flex justify-between pt-4">
                        <button type="button" wire:click="decline" wire:confirm="Decline this question?" class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800">
                            Decline Question
                        </button>
                        <div class="flex space-x-3">
                            <a href="{{ route('questions.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-500">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                                Submit Answer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
