<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Digest Preferences</h1>

    @if(session('message'))
        <div class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-6">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="space-y-6">
            <!-- Enable/Disable -->
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-medium text-gray-900 dark:text-white">Weekly Digest</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Receive a summary of activity you might have missed</p>
                </div>
                <button wire:click="$toggle('digestEnabled')" class="relative inline-flex h-6 w-11 items-center rounded-full {{ $digestEnabled ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600' }}">
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $digestEnabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                </button>
            </div>

            @if($digestEnabled)
                <!-- Frequency -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Frequency</label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly'] as $value => $label)
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="frequency" value="{{ $value }}" class="sr-only peer">
                                <div class="py-2 px-4 text-center border rounded-lg peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 text-sm">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Day of Week (for weekly) -->
                @if($frequency === 'weekly')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preferred Day</label>
                        <select wire:model="preferredDay" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            @foreach(['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preferred Time</label>
                    <input type="time" wire:model="preferredTime" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                </div>

                <!-- Content Types -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Include in digest</label>
                    <div class="space-y-2">
                        @foreach(['posts' => 'Popular Posts', 'events' => 'Upcoming Events', 'groups' => 'Group Activity', 'friends' => 'Friend Updates', 'trending' => 'Trending Topics', 'birthdays' => 'Birthdays'] as $value => $label)
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" wire:click="toggleContentType('{{ $value }}')" @checked(in_array($value, $includedContent)) class="rounded border-gray-300 dark:border-gray-600 text-indigo-600">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Additional Options -->
                <div class="space-y-3 pt-4 border-t dark:border-gray-700">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" wire:model="includeRecommendations" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600">
                        <div>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Include personalized recommendations</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">People you may know, groups to join, etc.</p>
                        </div>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" wire:model="includeAnalytics" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600">
                        <div>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Include your content analytics</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Views, likes, and engagement on your posts</p>
                        </div>
                    </label>
                </div>
            @endif

            <div class="pt-4">
                <button wire:click="save" class="w-full py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Save Preferences
                </button>
            </div>
        </div>
    </div>

    <!-- View History -->
    <div class="mt-6 text-center">
        <a href="{{ route('digest.history') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
            View past digests
        </a>
    </div>
</div>
