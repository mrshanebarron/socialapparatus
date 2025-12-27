<div>
    <button wire:click="openModal" type="button" class="inline-flex items-center justify-center p-2 rounded-full text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </button>

    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div wire:click="closeModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">New Conversation</h3>

                        <!-- Search Users -->
                        <div class="mb-4">
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search for people..."
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Selected Users -->
                        @if(count($selectedUsers) > 0)
                            <div class="mb-4 flex flex-wrap gap-2">
                                @foreach($selectedUsers as $userId)
                                    @php $selectedUser = \App\Models\User::find($userId) @endphp
                                    @if($selectedUser)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                            {{ $selectedUser->profile?->display_name ?? $selectedUser->name }}
                                            <button wire:click="toggleUser({{ $userId }})" type="button" class="ml-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <!-- Group Name (if multiple users) -->
                        @if($isGroup)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Group Name</label>
                                <input wire:model="groupName" type="text" placeholder="Enter group name..."
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('groupName') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <!-- Search Results -->
                        @if($search && strlen($search) >= 2)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-md max-h-48 overflow-y-auto">
                                @forelse($users as $user)
                                    <button wire:click="toggleUser({{ $user->id }})" type="button"
                                            class="w-full p-3 flex items-center space-x-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ in_array($user->id, $selectedUsers) ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                                        <img class="h-10 w-10 rounded-full object-cover"
                                             src="{{ $user->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}"
                                             alt="{{ $user->name }}">
                                        <div class="flex-1 text-left">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $user->profile?->display_name ?? $user->name }}
                                            </p>
                                            @if($user->profile?->username)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ '@' . $user->profile->username }}</p>
                                            @endif
                                        </div>
                                        @if(in_array($user->id, $selectedUsers))
                                            <svg class="h-5 w-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </button>
                                @empty
                                    <p class="p-3 text-sm text-gray-500 dark:text-gray-400 text-center">No users found</p>
                                @endforelse
                            </div>
                        @elseif($search)
                            <p class="text-sm text-gray-500 dark:text-gray-400">Type at least 2 characters to search...</p>
                        @endif
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="createConversation" type="button"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm {{ count($selectedUsers) === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ count($selectedUsers) === 0 ? 'disabled' : '' }}>
                            Start Conversation
                        </button>
                        <button wire:click="closeModal" type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
