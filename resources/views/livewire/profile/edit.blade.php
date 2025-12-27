<div class="max-w-4xl mx-auto">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Profile</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    This information will be displayed publicly so be careful what you share.
                </p>
            </div>
        </div>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <form wire:submit="save">
                @if (session()->has('message'))
                    <div class="mb-4 rounded-md bg-green-50 dark:bg-green-900/30 p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <p class="ml-3 text-sm font-medium text-green-800 dark:text-green-200">{{ session('message') }}</p>
                        </div>
                    </div>
                @endif

                <div class="shadow rounded-md overflow-hidden">
                    <div class="px-4 py-5 bg-white dark:bg-gray-800 space-y-6 sm:p-6">
                        <!-- Cover Photo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cover photo</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md relative overflow-hidden"
                                 style="height: 150px;">
                                @if($cover_photo)
                                    <img src="{{ $cover_photo->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                                @elseif($profile->cover_photo_url)
                                    <img src="{{ $profile->cover_photo_url }}" class="absolute inset-0 w-full h-full object-cover">
                                    <button type="button" wire:click="deleteCoverPhoto" class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                @else
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            <label class="relative cursor-pointer rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                                                <span>Upload a cover photo</span>
                                                <input type="file" wire:model="cover_photo" class="sr-only" accept="image/*">
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @error('cover_photo') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Avatar -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Avatar</label>
                            <div class="mt-1 flex items-center space-x-4">
                                <div class="relative">
                                    @if($avatar)
                                        <img src="{{ $avatar->temporaryUrl() }}" class="h-20 w-20 rounded-full object-cover">
                                    @else
                                        <img src="{{ $profile->avatar_url }}" class="h-20 w-20 rounded-full object-cover">
                                    @endif
                                </div>
                                <div class="flex space-x-2">
                                    <label class="cursor-pointer bg-white dark:bg-gray-700 py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <span>Change</span>
                                        <input type="file" wire:model="avatar" class="sr-only" accept="image/*">
                                    </label>
                                    @if($profile->avatar)
                                        <button type="button" wire:click="deleteAvatar" class="bg-red-100 dark:bg-red-900/30 py-2 px-3 border border-red-300 dark:border-red-700 rounded-md shadow-sm text-sm font-medium text-red-700 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900/50">
                                            Remove
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @error('avatar') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm">@</span>
                                <input type="text" wire:model="username" id="username" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="username">
                            </div>
                            @error('username') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Display Name -->
                        <div>
                            <label for="display_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Display Name</label>
                            <input type="text" wire:model="display_name" id="display_name" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('display_name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Bio -->
                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bio</label>
                            <textarea wire:model="bio" id="bio" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Tell us about yourself..."></textarea>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Max 500 characters.</p>
                            @error('bio') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                            <input type="text" wire:model="location" id="location" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="City, Country">
                            @error('location') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Website -->
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website</label>
                            <input type="url" wire:model="website" id="website" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="https://example.com">
                            @error('website') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Birthday -->
                        <div>
                            <label for="birthday" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birthday</label>
                            <input type="date" wire:model="birthday" id="birthday" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('birthday') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender</label>
                            <select wire:model="gender" id="gender" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Prefer not to say</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                            @error('gender') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Privacy Settings -->
                    <div class="px-4 py-5 bg-gray-50 dark:bg-gray-900 space-y-6 sm:p-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white">Privacy Settings</h4>

                        <!-- Profile Visibility -->
                        <div>
                            <label for="profile_visibility" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Who can see your profile?</label>
                            <select wire:model="profile_visibility" id="profile_visibility" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="public">Everyone</option>
                                <option value="friends">Friends only</option>
                                <option value="private">Only me</option>
                            </select>
                        </div>

                        <!-- Toggle Settings -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Show online status</label>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Let others see when you're online</p>
                                </div>
                                <button type="button" wire:click="$toggle('show_online_status')"
                                        class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 {{ $show_online_status ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    <span class="sr-only">Show online status</span>
                                    <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $show_online_status ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Show last seen</label>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Let others see when you were last active</p>
                                </div>
                                <button type="button" wire:click="$toggle('show_last_seen')"
                                        class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 {{ $show_last_seen ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    <span class="sr-only">Show last seen</span>
                                    <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $show_last_seen ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Allow friend requests</label>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Let others send you friend requests</p>
                                </div>
                                <button type="button" wire:click="$toggle('allow_friend_requests')"
                                        class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 {{ $allow_friend_requests ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    <span class="sr-only">Allow friend requests</span>
                                    <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $allow_friend_requests ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Allow messages</label>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Let others send you direct messages</p>
                                </div>
                                <button type="button" wire:click="$toggle('allow_messages')"
                                        class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 {{ $allow_messages ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    <span class="sr-only">Allow messages</span>
                                    <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $allow_messages ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900 text-right sm:px-6">
                        <a href="{{ route('profile.view', auth()->user()) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 mr-3">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
