<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Q&A</h3>

    @if($questions->isEmpty())
        <p class="text-gray-500 dark:text-gray-400 text-sm">No answered questions yet.</p>
    @else
        <div class="space-y-6">
            @foreach($questions as $question)
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                    <div class="flex items-center space-x-2 mb-2">
                        @if($question->is_anonymous)
                            <span class="text-sm text-gray-500 dark:text-gray-400">Anonymous</span>
                        @elseif($question->asker)
                            <img class="h-5 w-5 rounded-full object-cover" src="{{ $question->asker->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($question->asker->name) }}" alt="">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $question->asker->profile?->display_name ?? $question->asker->name }}
                            </span>
                        @endif
                    </div>

                    <p class="text-gray-900 dark:text-white font-medium">{{ $question->question }}</p>

                    <div class="mt-2 pl-4 border-l-2 border-indigo-500">
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $question->answer }}</p>
                    </div>

                    <div class="mt-3 flex items-center space-x-4">
                        @auth
                            <button wire:click="toggleLike({{ $question->id }})" class="flex items-center space-x-1 text-sm {{ $question->likes->contains('id', auth()->id()) ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}">
                                <svg class="h-4 w-4" fill="{{ $question->likes->contains('id', auth()->id()) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span>{{ $question->likes_count }}</span>
                            </button>
                        @else
                            <span class="flex items-center space-x-1 text-sm text-gray-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span>{{ $question->likes_count }}</span>
                            </span>
                        @endauth
                        <span class="text-xs text-gray-400">{{ $question->answered_at->diffForHumans() }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $questions->links() }}
        </div>
    @endif
</div>
