<div>
    @if($todayBirthdays->isNotEmpty() || $upcomingBirthdays->isNotEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-4">
            <h3 class="font-semibold text-gray-900 dark:text-white flex items-center mb-4">
                <svg class="h-5 w-5 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 5a3 3 0 015-2.236A3 3 0 0114.83 6H16a2 2 0 110 4h-5V9a1 1 0 10-2 0v1H4a2 2 0 110-4h1.17A3 3 0 015 5zm6 3a1 1 0 10-2 0 1 1 0 002 0zM6 15a1 1 0 10-2 0 1 1 0 002 0zm4 0a1 1 0 10-2 0 1 1 0 002 0zm4 0a1 1 0 10-2 0 1 1 0 002 0z" clip-rule="evenodd" />
                </svg>
                Birthdays
            </h3>

            @if(session()->has('message'))
                <div class="mb-4 p-3 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg text-sm">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Today's Birthdays -->
            @if($todayBirthdays->isNotEmpty())
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Today</h4>
                    <div class="space-y-3">
                        @foreach($todayBirthdays as $profile)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="relative">
                                        <a href="{{ route('profile.view', $profile->username ?? $profile->user_id) }}">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $profile->user->profile_photo_url }}" alt="{{ $profile->user->name }}">
                                        </a>
                                        <span class="absolute -bottom-1 -right-1 text-lg">🎂</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('profile.view', $profile->username ?? $profile->user_id) }}" class="font-medium text-gray-900 dark:text-white hover:underline">
                                            {{ $profile->user->name }}
                                        </a>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Birthday today!</p>
                                    </div>
                                </div>
                                <button wire:click="$set('wishingUserId', {{ $profile->user_id }})"
                                        class="text-sm px-3 py-1 bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-300 rounded-full hover:bg-pink-200 dark:hover:bg-pink-900/50">
                                    Send wish
                                </button>
                            </div>

                            @if($wishingUserId === $profile->user_id)
                                <div class="ml-13 mt-2 pl-13">
                                    <div class="flex space-x-2">
                                        <input type="text" wire:model="birthdayWish"
                                               placeholder="Write a birthday message..."
                                               class="flex-1 text-sm rounded-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <button wire:click="sendWish({{ $profile->user_id }})"
                                                class="px-4 py-2 bg-pink-600 text-white text-sm rounded-full hover:bg-pink-700">
                                            Post
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Upcoming Birthdays -->
            @if($upcomingBirthdays->isNotEmpty())
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Coming Up</h4>
                    <div class="space-y-2">
                        @foreach($upcomingBirthdays->take(5) as $profile)
                            @php
                                $birthday = $profile->birthday->copy()->year(now()->year);
                                if ($birthday->lt(now())) {
                                    $birthday->addYear();
                                }
                                $daysUntil = now()->diffInDays($birthday);
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('profile.view', $profile->username ?? $profile->user_id) }}">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ $profile->user->profile_photo_url }}" alt="{{ $profile->user->name }}">
                                    </a>
                                    <div>
                                        <a href="{{ route('profile.view', $profile->username ?? $profile->user_id) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:underline">
                                            {{ $profile->user->name }}
                                        </a>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $birthday->format('M j') }}
                                    ({{ $daysUntil }} {{ Str::plural('day', $daysUntil) }})
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
