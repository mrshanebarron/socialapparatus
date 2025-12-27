<div class="bg-white dark:bg-gray-800 rounded-lg shadow"
     x-data="postComposer()"
     @click.away="showMentions = false; showEmojis = false">
    <form wire:submit.prevent="createPost">
        <div class="p-4">
            <div class="flex items-start space-x-4">
                <img class="h-10 w-10 rounded-full object-cover" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}">
                <div class="flex-1 relative">
                    <textarea
                        x-ref="textarea"
                        wire:model="body"
                        @input="handleInput($event)"
                        @keydown="handleKeydown($event)"
                        rows="3"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 resize-none pr-10"
                        placeholder="What's on your mind, {{ auth()->user()->name }}?"
                    ></textarea>

                    <!-- Emoji Button (inline) -->
                    <button type="button"
                            @click="showEmojis = !showEmojis; showMentions = false"
                            class="absolute right-2 top-2 p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>

                    <!-- Emoji Picker -->
                    <div x-show="showEmojis"
                         x-transition
                         class="absolute right-0 top-12 z-50 bg-white dark:bg-gray-700 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 p-3 w-72">
                        <div class="grid grid-cols-8 gap-1 max-h-48 overflow-y-auto">
                            <template x-for="(emoji, index) in emojis" :key="index">
                                <button type="button"
                                        @click="insertEmoji(emoji)"
                                        class="p-1 hover:bg-gray-100 dark:hover:bg-gray-600 rounded text-xl"
                                        x-text="emoji"></button>
                            </template>
                        </div>
                    </div>

                    <!-- @Mention Autocomplete -->
                    <div x-show="showMentions && mentionResults.length > 0"
                         x-transition
                         class="absolute left-0 right-0 top-full mt-1 z-50 bg-white dark:bg-gray-700 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 overflow-hidden">
                        <template x-for="(user, index) in mentionResults" :key="user.id">
                            <button type="button"
                                    @click="selectMention(user)"
                                    :class="{ 'bg-indigo-50 dark:bg-indigo-900/50': selectedMentionIndex === index }"
                                    class="w-full flex items-center px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-600 text-left">
                                <img :src="user.avatar" class="w-8 h-8 rounded-full mr-3" :alt="user.name">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white" x-text="user.name"></div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400" x-text="'@' + user.username"></div>
                                </div>
                            </button>
                        </template>
                    </div>

                    @error('body') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Media Preview -->
            @if (count($media) > 0)
                <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @foreach ($media as $index => $file)
                        <div class="relative">
                            <img src="{{ $file->temporaryUrl() }}" class="h-24 w-full object-cover rounded-lg">
                            <button
                                type="button"
                                wire:click="removeMedia({{ $index }})"
                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Location Display -->
            @if($locationName)
                <div class="mt-3 flex items-center text-sm text-indigo-600 dark:text-indigo-400">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>{{ $locationName }}</span>
                    <button type="button" wire:click="$set('locationName', '')" class="ml-2 text-gray-400 hover:text-gray-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Feeling/Activity Display -->
            @if($feeling || $activity)
                <div class="mt-3 flex items-center text-sm text-indigo-600 dark:text-indigo-400">
                    @if($feeling)
                        <span>{{ $feelingEmojis[$feeling] ?? '' }} feeling {{ $feeling }}</span>
                    @endif
                    @if($activity)
                        <span class="ml-1">{{ $activities[$activity] ?? $activity }} {{ $activityDetail }}</span>
                    @endif
                    <button type="button" wire:click="toggleFeelingForm" class="ml-2 text-gray-400 hover:text-gray-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            <!-- GIF Preview -->
            @if($gifUrl)
                <div class="mt-3 relative inline-block">
                    <img src="{{ $gifUrl }}" alt="Selected GIF" class="rounded-lg max-h-40">
                    <button type="button" wire:click="clearGif" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Background Preview -->
            @if($backgroundGradient)
                <div class="mt-3 flex items-center text-sm">
                    <div class="w-6 h-6 rounded" style="background: {{ $backgroundGradient }};"></div>
                    <span class="ml-2 text-gray-600 dark:text-gray-400">Background color selected</span>
                    <button type="button" wire:click="clearBackground" class="ml-2 text-gray-400 hover:text-gray-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Poll Form -->
            @if($showPollForm)
                <div class="mt-4 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-medium text-gray-900 dark:text-white">Create Poll</h4>
                        <button type="button" wire:click="togglePollForm" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <input type="text" wire:model="pollQuestion" placeholder="Ask a question..."
                           class="w-full mb-3 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                    <div class="space-y-2">
                        @foreach($pollOptions as $index => $option)
                            <div class="flex items-center space-x-2">
                                <input type="text" wire:model="pollOptions.{{ $index }}" placeholder="Option {{ $index + 1 }}"
                                       class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                @if(count($pollOptions) > 2)
                                    <button type="button" wire:click="removePollOption({{ $index }})" class="text-red-500 hover:text-red-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @if(count($pollOptions) < 10)
                        <button type="button" wire:click="addPollOption" class="mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                            + Add option
                        </button>
                    @endif
                    <div class="mt-3 flex items-center justify-between">
                        <label class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <input type="checkbox" wire:model="pollAllowMultiple" class="mr-2 rounded border-gray-300 dark:border-gray-600">
                            Allow multiple answers
                        </label>
                        <input type="datetime-local" wire:model="pollEndsAt"
                               class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                               placeholder="Poll end time (optional)">
                    </div>
                </div>
            @endif

            <!-- Location Form -->
            @if($showLocationForm && !$locationName)
                <div class="mt-4 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-medium text-gray-900 dark:text-white">Check In</h4>
                        <button type="button" wire:click="toggleLocationForm" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <input type="text" wire:model="locationName" placeholder="Enter a location..."
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                    <p class="mt-1 text-xs text-gray-500">Type a place name (e.g., "Starbucks, Downtown" or "Central Park")</p>
                </div>
            @endif

            <!-- Schedule Form -->
            @if($showScheduleForm)
                <div class="mt-4 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-medium text-gray-900 dark:text-white">Schedule Post</h4>
                        <button type="button" wire:click="toggleScheduleForm" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <input type="datetime-local" wire:model="scheduledAt"
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm"
                           min="{{ now()->format('Y-m-d\TH:i') }}">
                    <p class="mt-1 text-xs text-gray-500">Select when you want this post to be published</p>
                </div>
            @endif

            <!-- Feeling/Activity Form -->
            @if($showFeelingForm)
                <div class="mt-4 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-medium text-gray-900 dark:text-white">How are you feeling?</h4>
                        <button type="button" wire:click="toggleFeelingForm" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-5 gap-2 mb-4">
                        @foreach($feelings as $key => $label)
                            <button type="button" wire:click="setFeeling('{{ $key }}')"
                                    class="flex flex-col items-center p-2 rounded-lg border {{ $feeling === $key ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/50' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <span class="text-xl">{{ $feelingEmojis[$key] }}</span>
                                <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $label }}</span>
                            </button>
                        @endforeach
                    </div>

                    <h4 class="font-medium text-gray-900 dark:text-white mb-3">Or what are you doing?</h4>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($activities as $key => $label)
                            <button type="button" wire:click="setActivity('{{ $key }}')"
                                    class="text-left px-3 py-2 rounded-lg border text-sm {{ $activity === $key ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/50' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>

                    @if($activity)
                        <input type="text" wire:model="activityDetail" placeholder="Add details..."
                               class="mt-3 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                    @endif
                </div>
            @endif

            <!-- Background Color Picker -->
            @if($showBackgroundPicker)
                <div class="mt-4 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-medium text-gray-900 dark:text-white">Choose Background</h4>
                        <button type="button" wire:click="toggleBackgroundPicker" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-5 gap-2">
                        @foreach($backgroundColors as $class => $gradient)
                            <button type="button" wire:click="setBackground('{{ $gradient }}')"
                                    class="aspect-square rounded-lg {{ $backgroundGradient === $gradient ? 'ring-2 ring-indigo-500' : '' }}"
                                    style="background: {{ $gradient }};"></button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- GIF Picker -->
            @if($showGifPicker)
                <div class="mt-4 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-medium text-gray-900 dark:text-white">Choose a GIF</h4>
                        <button type="button" wire:click="toggleGifPicker" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                        Paste a GIF URL from Giphy, Tenor, or any image hosting service:
                    </p>
                    <input type="url" wire:model="gifUrl" placeholder="https://media.giphy.com/..."
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                    @if($gifUrl)
                        <div class="mt-3">
                            <img src="{{ $gifUrl }}" alt="GIF Preview" class="rounded-lg max-h-40">
                        </div>
                    @endif
                </div>
            @endif

            <!-- Link URL Input -->
            @if($linkUrl || $showPollForm === false && count($media) === 0)
                <div class="mt-4" x-show="$wire.linkUrl || ($wire.showPollForm === false && $wire.media.length === 0)">
                    <input type="url" wire:model.blur="linkUrl" placeholder="Add a link URL (optional)..."
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                </div>
            @endif
        </div>

        <!-- Actions Bar -->
        <div class="border-t border-gray-200 dark:border-gray-700">
            <!-- Tool Icons Row -->
            <div class="px-4 py-2 flex items-center justify-between">
                <div class="flex items-center space-x-1">
                    <!-- Photo Upload -->
                    <label class="p-2 rounded-lg cursor-pointer text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 transition-colors" title="Add Photo">
                        <input type="file" wire:model="media" multiple accept="image/*" class="hidden">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </label>

                    <!-- Poll Button -->
                    <button type="button" wire:click="togglePollForm"
                            class="p-2 rounded-lg transition-colors {{ $showPollForm ? 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-900/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700' }}"
                            title="Create Poll">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </button>

                    <!-- Location Button -->
                    <button type="button" wire:click="toggleLocationForm"
                            class="p-2 rounded-lg transition-colors {{ $showLocationForm || $locationName ? 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-900/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700' }}"
                            title="Check In">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>

                    <!-- Schedule Button -->
                    <button type="button" wire:click="toggleScheduleForm"
                            class="p-2 rounded-lg transition-colors {{ $showScheduleForm ? 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-900/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700' }}"
                            title="Schedule Post">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </button>

                    <!-- Feeling/Activity Button -->
                    <button type="button" wire:click="toggleFeelingForm"
                            class="p-2 rounded-lg transition-colors {{ $showFeelingForm || $feeling || $activity ? 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-900/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700' }}"
                            title="Feeling/Activity">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>

                    <!-- Background Color Button -->
                    <button type="button" wire:click="toggleBackgroundPicker"
                            class="p-2 rounded-lg transition-colors {{ $showBackgroundPicker || $backgroundGradient ? 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-900/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700' }}"
                            title="Background Color">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                    </button>

                    <!-- GIF Button -->
                    <button type="button" wire:click="toggleGifPicker"
                            class="p-2 rounded-lg transition-colors {{ $showGifPicker || $gifUrl ? 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-900/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700' }}"
                            title="Add GIF">
                        <span class="text-xs font-bold">GIF</span>
                    </button>
                </div>

                <!-- Visibility Selector -->
                <div class="relative" x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="flex items-center px-3 py-1.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        @if ($visibility === 'public')
                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Public
                        @elseif ($visibility === 'friends')
                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Friends
                        @else
                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Only Me
                        @endif
                        <svg class="h-4 w-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-1 w-36 bg-white dark:bg-gray-700 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 z-20 py-1">
                        <button type="button" wire:click="$set('visibility', 'public')" @click="open = false" class="flex items-center w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Public
                        </button>
                        <button type="button" wire:click="$set('visibility', 'friends')" @click="open = false" class="flex items-center w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Friends
                        </button>
                        <button type="button" wire:click="$set('visibility', 'private')" @click="open = false" class="flex items-center w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Only Me
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons Row -->
            <div class="px-4 py-3 flex items-center justify-end space-x-2">
                @if($showScheduleForm && $scheduledAt)
                    <button
                        type="button"
                        wire:click="schedulePost"
                        class="px-4 py-2 text-sm font-medium text-white bg-gray-600 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="schedulePost">Schedule</span>
                        <span wire:loading wire:target="schedulePost">Scheduling...</span>
                    </button>
                @endif
                <button
                    type="button"
                    wire:click="saveDraft"
                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="saveDraft">Save Draft</span>
                    <span wire:loading wire:target="saveDraft">Saving...</span>
                </button>
                <button
                    type="submit"
                    class="px-5 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="createPost">Post</span>
                    <span wire:loading wire:target="createPost">Posting...</span>
                </button>
            </div>
        </div>
    </form>

    <!-- Loading indicator for media upload -->
    <div wire:loading wire:target="media" class="px-4 pb-4">
        <div class="flex items-center text-sm text-gray-500">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Uploading...
        </div>
    </div>

    @script
    <script>
    Alpine.data('postComposer', () => ({
    showEmojis: false,
    showMentions: false,
    mentionResults: [],
    selectedMentionIndex: 0,
    mentionStartPos: 0,
    mentionQuery: '',
    debounceTimer: null,

    emojis: [
        '😀', '😃', '😄', '😁', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌', '😍', '🥰', '😘',
        '😗', '😙', '😚', '😋', '😛', '😜', '🤪', '😝', '🤑', '🤗', '🤭', '🤫', '🤔', '🤐', '🤨', '😐',
        '😑', '😶', '😏', '😒', '🙄', '😬', '🤥', '🥹', '😔', '😪', '🤤', '😴', '😷', '🤒', '🤕', '🤢',
        '🤮', '🤧', '🥵', '🥶', '🥴', '😵', '🤯', '🤠', '🥳', '🥸', '😎', '🤓', '🧐', '😕', '😟', '🙁',
        '☹️', '😮', '😯', '😲', '😳', '🥺', '😦', '😧', '😨', '😰', '😥', '😢', '😭', '😱', '😖', '😣',
        '😞', '😓', '😩', '😫', '🥱', '😤', '😡', '😠', '🤬', '😈', '👿', '💀', '☠️', '💩', '🤡', '👹',
        '❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '🤍', '🤎', '💔', '❣️', '💕', '💞', '💓', '💗', '💖',
        '💘', '💝', '👍', '👎', '👏', '🙌', '👐', '🤲', '🤝', '🙏', '✌️', '🤞', '🤟', '🤘', '👌', '🤌',
        '🔥', '✨', '🎉', '🎊', '💯', '💢', '💥', '💫', '💦', '💨', '🕳️', '💣', '💬', '👁️‍🗨️', '🗨️', '🗯️'
    ],

    init() {
        this.$watch('$wire.mentionResults', (value) => {
            this.mentionResults = value;
        });
    },

    handleInput(event) {
        const textarea = event.target;
        const value = textarea.value;
        const cursorPos = textarea.selectionStart;

        // Find if we're in a mention context
        const textBeforeCursor = value.substring(0, cursorPos);
        const mentionMatch = textBeforeCursor.match(/@(\w*)$/);

        if (mentionMatch) {
            this.mentionStartPos = cursorPos - mentionMatch[0].length;
            this.mentionQuery = mentionMatch[1];
            this.showMentions = true;
            this.showEmojis = false;
            this.selectedMentionIndex = 0;

            // Debounce the search
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.$wire.searchMentions(this.mentionQuery);
            }, 150);
        } else {
            this.showMentions = false;
        }
    },

    handleKeydown(event) {
        if (!this.showMentions || this.mentionResults.length === 0) return;

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            this.selectedMentionIndex = Math.min(this.selectedMentionIndex + 1, this.mentionResults.length - 1);
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            this.selectedMentionIndex = Math.max(this.selectedMentionIndex - 1, 0);
        } else if (event.key === 'Enter' || event.key === 'Tab') {
            if (this.showMentions && this.mentionResults.length > 0) {
                event.preventDefault();
                this.selectMention(this.mentionResults[this.selectedMentionIndex]);
            }
        } else if (event.key === 'Escape') {
            this.showMentions = false;
        }
    },

    selectMention(user) {
        const textarea = this.$refs.textarea;
        const value = textarea.value;
        const before = value.substring(0, this.mentionStartPos);
        const after = value.substring(textarea.selectionStart);

        const newValue = before + '@' + user.username + ' ' + after;
        this.$wire.set('body', newValue);

        this.showMentions = false;
        this.mentionResults = [];

        // Set cursor position after mention
        this.$nextTick(() => {
            const newPos = this.mentionStartPos + user.username.length + 2;
            textarea.focus();
            textarea.setSelectionRange(newPos, newPos);
        });
    },

    insertEmoji(emoji) {
        const textarea = this.$refs.textarea;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const value = textarea.value;

        const newValue = value.substring(0, start) + emoji + value.substring(end);
        this.$wire.set('body', newValue);

        this.$nextTick(() => {
            const newPos = start + emoji.length;
            textarea.focus();
            textarea.setSelectionRange(newPos, newPos);
        });

        this.showEmojis = false;
    }
}));
    </script>
    @endscript
</div>
