<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Questions</h2>

                <!-- Filter Tabs -->
                <div class="flex space-x-4 mb-6 border-b border-gray-200 dark:border-gray-700">
                    <button wire:click="setFilter('pending')" class="pb-3 px-1 text-sm font-medium {{ $filter === 'pending' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                        Pending
                        @if($counts['pending'] > 0)
                            <span class="ml-1 px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 rounded-full text-xs">{{ $counts['pending'] }}</span>
                        @endif
                    </button>
                    <button wire:click="setFilter('answered')" class="pb-3 px-1 text-sm font-medium {{ $filter === 'answered' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                        Answered
                        <span class="ml-1 text-gray-400 text-xs">({{ $counts['answered'] }})</span>
                    </button>
                    <button wire:click="setFilter('all')" class="pb-3 px-1 text-sm font-medium {{ $filter === 'all' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                        All
                    </button>
                </div>

                @if($questions->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No questions</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            @if($filter === 'pending')
                                No pending questions to answer.
                            @else
                                No questions found.
                            @endif
                        </p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($questions as $question)
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            @if($question->is_anonymous)
                                                <span class="text-sm text-gray-500 dark:text-gray-400">Anonymous</span>
                                            @elseif($question->asker)
                                                <img class="h-6 w-6 rounded-full object-cover" src="{{ $question->asker->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($question->asker->name) }}" alt="">
                                                <a href="{{ route('profile.view', $question->asker) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:underline">
                                                    {{ $question->asker->profile?->display_name ?? $question->asker->name }}
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-500 dark:text-gray-400">Deleted user</span>
                                            @endif
                                            <span class="text-xs text-gray-400">{{ $question->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-900 dark:text-white">{{ $question->question }}</p>

                                        @if($question->answer)
                                            <div class="mt-3 pl-4 border-l-2 border-indigo-500">
                                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $question->answer }}</p>
                                                <p class="text-xs text-gray-400 mt-1">Answered {{ $question->answered_at->diffForHumans() }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    @if($question->status === 'pending')
                                        <a href="{{ route('questions.answer', $question) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                                            Answer
                                        </a>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs rounded-full
                                            @if($question->status === 'answered') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                            @elseif($question->status === 'declined') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100
                                            @endif">
                                            {{ ucfirst($question->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $questions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
