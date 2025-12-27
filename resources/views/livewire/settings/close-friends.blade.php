<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Close Friends</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">People who can see your close friends only content</p>
                    </div>
                    <button wire:click="$set('showAddModal', true)" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Add Friend
                    </button>
                </div>

                @if($closeFriends->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No close friends yet</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add friends to share exclusive content with them.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($closeFriends as $friend)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $friend->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($friend->name) }}" alt="">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $friend->profile?->display_name ?? $friend->name }}</p>
                                        @if($friend->profile?->username)
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ '@' . $friend->profile->username }}</p>
                                        @endif
                                    </div>
                                </div>
                                <button wire:click="removeCloseFriend({{ $friend->id }})" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Remove
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Close Friend Modal -->
    @if($showAddModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Add Close Friend</h3>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search friends..." class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                    @if(count($searchResults) > 0)
                        <div class="mt-4 space-y-2 max-h-60 overflow-y-auto">
                            @foreach($searchResults as $friend)
                                <button wire:click="addCloseFriend({{ $friend->id }})" class="w-full flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ $friend->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($friend->name) }}" alt="">
                                        <span class="text-gray-900 dark:text-white">{{ $friend->profile?->display_name ?? $friend->name }}</span>
                                    </div>
                                    <span class="text-indigo-600 text-sm">Add</span>
                                </button>
                            @endforeach
                        </div>
                    @elseif(strlen($search) >= 2)
                        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400 text-center">No friends found</p>
                    @endif

                    <div class="mt-6 flex justify-end">
                        <button wire:click="$set('showAddModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-500">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
