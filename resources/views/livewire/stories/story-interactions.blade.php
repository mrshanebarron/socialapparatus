<div class="space-y-4">
    @if($story->user_id === auth()->id())
        <!-- Add Interaction Buttons (Story Owner Only) -->
        <div class="flex space-x-2">
            <button wire:click="openAddModal('poll')" class="px-3 py-1 text-xs bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 rounded-full hover:bg-purple-200 dark:hover:bg-purple-800">
                + Poll
            </button>
            <button wire:click="openAddModal('question')" class="px-3 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full hover:bg-blue-200 dark:hover:bg-blue-800">
                + Question
            </button>
            <button wire:click="openAddModal('slider')" class="px-3 py-1 text-xs bg-pink-100 dark:bg-pink-900 text-pink-700 dark:text-pink-300 rounded-full hover:bg-pink-200 dark:hover:bg-pink-800">
                + Slider
            </button>
            <button wire:click="openAddModal('quiz')" class="px-3 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full hover:bg-green-200 dark:hover:bg-green-800">
                + Quiz
            </button>
        </div>
    @endif

    <!-- Polls -->
    @foreach($polls as $poll)
        <div class="bg-white/10 backdrop-blur rounded-lg p-4">
            <p class="text-white font-medium mb-3">{{ $poll->question }}</p>
            <div class="space-y-2">
                @foreach($poll->options as $index => $option)
                    @php
                        $votes = $poll->votes->where('option_index', $index)->count();
                        $percentage = $poll->total_votes > 0 ? round(($votes / $poll->total_votes) * 100) : 0;
                        $hasVoted = $poll->hasVoted(auth()->user());
                        $userVote = $poll->votes->where('user_id', auth()->id())->first()?->option_index;
                    @endphp
                    <button
                        wire:click="votePoll({{ $poll->id }}, {{ $index }})"
                        @if($hasVoted) disabled @endif
                        class="w-full relative overflow-hidden rounded-lg {{ $hasVoted ? 'cursor-default' : 'hover:bg-white/20' }}"
                    >
                        <div class="absolute inset-0 bg-indigo-500/30 transition-all" style="width: {{ $hasVoted ? $percentage : 0 }}%"></div>
                        <div class="relative flex justify-between items-center px-4 py-2 text-white">
                            <span class="{{ $userVote === $index ? 'font-bold' : '' }}">{{ $option }}</span>
                            @if($hasVoted)
                                <span class="text-sm">{{ $percentage }}%</span>
                            @endif
                        </div>
                    </button>
                @endforeach
            </div>
            <p class="text-white/60 text-xs mt-2">{{ $poll->total_votes }} votes</p>
        </div>
    @endforeach

    <!-- Questions -->
    @foreach($questions as $question)
        <div class="bg-white/10 backdrop-blur rounded-lg p-4">
            <p class="text-white font-medium mb-3">{{ $question->question }}</p>
            @if($story->user_id !== auth()->id())
                <form wire:submit.prevent="submitQuestionResponse({{ $question->id }})">
                    <input type="text"
                           wire:model="questionResponse"
                           placeholder="{{ $question->placeholder }}"
                           class="w-full bg-white/20 border-0 rounded-lg px-4 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-white/50">
                </form>
            @else
                <!-- Show responses to owner -->
                <div class="space-y-2 max-h-32 overflow-y-auto">
                    @foreach($question->responses as $response)
                        <div class="flex items-center space-x-2 text-white/80 text-sm">
                            <img src="{{ $response->user->profile_photo_url }}" class="w-6 h-6 rounded-full">
                            <span>{{ $response->response }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach

    <!-- Sliders -->
    @foreach($sliders as $slider)
        <div class="bg-white/10 backdrop-blur rounded-lg p-4">
            <p class="text-white font-medium mb-3">{{ $slider->question }}</p>
            <div class="flex items-center space-x-3">
                <span class="text-2xl">{{ $slider->emoji }}</span>
                <input type="range"
                       wire:model="sliderValue"
                       wire:change="submitSliderResponse({{ $slider->id }})"
                       min="0" max="100"
                       class="flex-1 h-2 bg-white/30 rounded-lg appearance-none cursor-pointer">
                <span class="text-2xl opacity-{{ $sliderValue }}">{{ $slider->emoji }}</span>
            </div>
            @if($slider->average_value)
                <p class="text-white/60 text-xs mt-2">Average: {{ round($slider->average_value) }}%</p>
            @endif
        </div>
    @endforeach

    <!-- Quizzes -->
    @foreach($quizzes as $quiz)
        @php
            $userAnswer = $quiz->answers->where('user_id', auth()->id())->first();
            $hasAnswered = $userAnswer !== null;
        @endphp
        <div class="bg-white/10 backdrop-blur rounded-lg p-4">
            <p class="text-white font-medium mb-3">{{ $quiz->question }}</p>
            <div class="grid grid-cols-2 gap-2">
                @foreach($quiz->options as $index => $option)
                    @php
                        $isCorrect = $index === $quiz->correct_option;
                        $isSelected = $userAnswer?->selected_option === $index;
                    @endphp
                    <button
                        wire:click="answerQuiz({{ $quiz->id }}, {{ $index }})"
                        @if($hasAnswered) disabled @endif
                        class="px-4 py-2 rounded-lg text-white transition
                            @if($hasAnswered)
                                @if($isCorrect) bg-green-500
                                @elseif($isSelected) bg-red-500
                                @else bg-white/20 @endif
                            @else
                                bg-white/20 hover:bg-white/30
                            @endif"
                    >
                        {{ $option }}
                        @if($hasAnswered && $isSelected)
                            @if($isCorrect) ✓ @else ✗ @endif
                        @endif
                    </button>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- Add Interaction Modal -->
    @if($showAddModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data>
            <div class="fixed inset-0 bg-black/50" wire:click="closeAddModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Add {{ ucfirst($interactionType) }}
                </h3>

                @if($interactionType === 'poll')
                    <div class="space-y-4">
                        <input type="text" wire:model="pollQuestion" placeholder="Ask a question..."
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        @foreach($pollOptions as $index => $option)
                            <div class="flex items-center space-x-2">
                                <input type="text" wire:model="pollOptions.{{ $index }}" placeholder="Option {{ $index + 1 }}"
                                       class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                @if(count($pollOptions) > 2)
                                    <button wire:click="removePollOption({{ $index }})" class="text-red-500 hover:text-red-700">×</button>
                                @endif
                            </div>
                        @endforeach
                        @if(count($pollOptions) < 4)
                            <button wire:click="addPollOption" class="text-indigo-500 hover:text-indigo-700 text-sm">+ Add option</button>
                        @endif
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" wire:model="allowMultiple" class="rounded">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Allow multiple selections</span>
                        </label>
                        <button wire:click="createPoll" class="w-full py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Create Poll</button>
                    </div>
                @elseif($interactionType === 'question')
                    <div class="space-y-4">
                        <input type="text" wire:model="questionText" placeholder="Ask me anything..."
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        <input type="text" wire:model="placeholderText" placeholder="Placeholder text"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        <button wire:click="createQuestion" class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create Question</button>
                    </div>
                @elseif($interactionType === 'slider')
                    <div class="space-y-4">
                        <input type="text" wire:model="sliderQuestion" placeholder="How much do you...?"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Choose emoji</label>
                            <div class="flex space-x-2 mt-2">
                                @foreach(['😍', '🔥', '❤️', '😂', '👍', '⭐'] as $emoji)
                                    <button wire:click="$set('sliderEmoji', '{{ $emoji }}')"
                                            class="text-2xl p-2 rounded {{ $sliderEmoji === $emoji ? 'bg-indigo-100 dark:bg-indigo-900' : '' }}">
                                        {{ $emoji }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <button wire:click="createSlider" class="w-full py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">Create Slider</button>
                    </div>
                @elseif($interactionType === 'quiz')
                    <div class="space-y-4">
                        <input type="text" wire:model="quizQuestion" placeholder="Quiz question..."
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        @foreach($quizOptions as $index => $option)
                            <div class="flex items-center space-x-2">
                                <input type="radio" wire:model="correctOption" value="{{ $index }}" class="text-green-500">
                                <input type="text" wire:model="quizOptions.{{ $index }}" placeholder="Option {{ $index + 1 }}"
                                       class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            </div>
                        @endforeach
                        <p class="text-xs text-gray-500">Select the correct answer</p>
                        <button wire:click="createQuiz" class="w-full py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Create Quiz</button>
                    </div>
                @endif

                <button wire:click="closeAddModal" class="mt-4 w-full py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">Cancel</button>
            </div>
        </div>
    @endif
</div>
