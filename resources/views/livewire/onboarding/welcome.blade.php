<div class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center py-12 px-4">
    <div class="max-w-lg w-full bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
        <!-- Progress Bar -->
        <div class="h-2 bg-gray-200 dark:bg-gray-700">
            <div class="h-2 bg-indigo-600 transition-all duration-300" style="width: {{ ($step / 5) * 100 }}%"></div>
        </div>

        <div class="p-8">
            <!-- Step 1: Welcome & Name -->
            @if($step === 1)
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome to {{ config('app.name') }}!</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Let's set up your profile in just a few steps.</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">What should we call you?</label>
                        <input type="text" wire:model="displayName"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Your display name">
                        @error('displayName') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

            <!-- Step 2: Profile Details -->
            @if($step === 2)
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tell us about yourself</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Help others get to know you better.</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Headline</label>
                        <input type="text" wire:model="headline"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="e.g., Software Developer | Coffee Lover">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bio</label>
                        <textarea wire:model="bio" rows="3"
                                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Write a short bio..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                        <input type="text" wire:model="location"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="City, Country">
                    </div>
                </div>
            @endif

            <!-- Step 3: Avatar -->
            @if($step === 3)
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Add a profile photo</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Help your friends recognize you.</p>
                </div>

                <div class="flex flex-col items-center">
                    <div class="relative mb-6">
                        @if($avatar)
                            <img src="{{ $avatar->temporaryUrl() }}" class="w-32 h-32 rounded-full object-cover">
                        @else
                            <div class="w-32 h-32 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        @endif
                        <label for="avatar-upload" class="absolute bottom-0 right-0 w-10 h-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </label>
                        <input type="file" id="avatar-upload" wire:model="avatar" accept="image/*" class="hidden">
                    </div>
                    @error('avatar') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>
            @endif

            <!-- Step 4: Interests -->
            @if($step === 4)
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">What are your interests?</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Select topics you're interested in.</p>
                </div>

                <div class="flex flex-wrap gap-2 justify-center">
                    @foreach($interests as $interest)
                        <button wire:click="toggleInterest('{{ $interest }}')"
                                class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ in_array($interest, $selectedInterests) ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                            {{ $interest }}
                        </button>
                    @endforeach
                </div>
            @endif

            <!-- Step 5: Find Friends -->
            @if($step === 5)
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Find people to connect with</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Start building your network.</p>
                </div>

                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach($suggestedUsers as $user)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $user['profile_photo_url'] ?? 'https://ui-avatars.com/api/?name='.urlencode($user['name']) }}"
                                     class="w-10 h-10 rounded-full">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $user['name'] }}</p>
                                </div>
                            </div>
                            <button wire:click="sendFriendRequest({{ $user['id'] }})"
                                    class="px-3 py-1 text-xs font-medium text-indigo-600 bg-indigo-100 hover:bg-indigo-200 dark:text-indigo-400 dark:bg-indigo-900/50 rounded-full">
                                Add Friend
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Navigation Buttons -->
            <div class="mt-8 flex justify-between">
                @if($step > 1)
                    <button wire:click="prevStep"
                            class="px-6 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        Back
                    </button>
                @else
                    <button wire:click="skip" class="px-6 py-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 text-sm">
                        Skip for now
                    </button>
                @endif

                @if($step < 5)
                    <button wire:click="nextStep"
                            class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium">
                        Continue
                    </button>
                @else
                    <button wire:click="complete"
                            class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium">
                        Get Started
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
