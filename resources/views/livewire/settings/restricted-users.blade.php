<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Restricted Users</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">These users can only see your public posts and limited profile info</p>
                    </div>
                    <button wire:click="$set('showAddModal', true)" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Restrict User
                    </button>
                </div>

                @if($restrictedUsers->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No restricted users</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Restrict users to limit what they can see without blocking them.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($restrictedUsers as $restricted)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $restricted->restrictedUser->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($restricted->restrictedUser->name) }}" alt="">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $restricted->restrictedUser->profile?->display_name ?? $restricted->restrictedUser->name }}</p>
                                        @if($restricted->reason)
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $restricted->reason }}</p>
                                        @endif
                                    </div>
                                </div>
                                <button wire:click="unrestrictUser({{ $restricted->restricted_user_id }})" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    Unrestrict
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Restricted User Modal -->
    @if($showAddModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Restrict User</h3>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search users..." class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reason (optional)</label>
                        <input wire:model="restrictReason" type="text" placeholder="Why are you restricting this user?" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    @if(count($searchResults) > 0)
                        <div class="mt-4 space-y-2 max-h-60 overflow-y-auto">
                            @foreach($searchResults as $user)
                                <button wire:click="restrictUser({{ $user->id }})" class="w-full flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ $user->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" alt="">
                                        <span class="text-gray-900 dark:text-white">{{ $user->profile?->display_name ?? $user->name }}</span>
                                    </div>
                                    <span class="text-orange-600 text-sm">Restrict</span>
                                </button>
                            @endforeach
                        </div>
                    @elseif(strlen($search) >= 2)
                        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400 text-center">No users found</p>
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
