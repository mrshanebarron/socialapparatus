<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('groups.show', $group) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
            &larr; Back to {{ $group->name }}
        </a>
        <h1 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">Group Members</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <!-- Tabs -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex space-x-4">
                <button wire:click="setTab('members')"
                        class="pb-2 px-1 text-sm font-medium {{ $tab === 'members' ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                    Members ({{ $group->members_count }})
                </button>
                @if($isModerator && $pendingCount > 0)
                    <button wire:click="setTab('pending')"
                            class="pb-2 px-1 text-sm font-medium {{ $tab === 'pending' ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        Pending ({{ $pendingCount }})
                    </button>
                @endif
            </div>
        </div>

        <!-- Search -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search members..."
                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <!-- Members List -->
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($members as $member)
                <div class="p-4 flex items-center justify-between">
                    <a href="{{ route('profile.view', $member->user) }}" class="flex items-center space-x-4">
                        <img class="h-12 w-12 rounded-full object-cover"
                             src="{{ $member->user->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($member->user->name) }}"
                             alt="{{ $member->user->name }}">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $member->user->profile?->display_name ?? $member->user->name }}
                                @if($member->role !== 'member')
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $member->role === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                        {{ ucfirst($member->role) }}
                                    </span>
                                @endif
                            </p>
                            @if($member->joined_at)
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Joined {{ $member->joined_at->diffForHumans() }}
                                </p>
                            @else
                                <p class="text-xs text-yellow-600 dark:text-yellow-400">
                                    Pending approval
                                </p>
                            @endif
                        </div>
                    </a>

                    @if($isModerator && $member->user_id !== $group->owner_id && $member->user_id !== auth()->id())
                        <div class="flex items-center space-x-2">
                            @if($tab === 'pending')
                                <button wire:click="approveMember({{ $member->id }})"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                    Approve
                                </button>
                                <button wire:click="rejectMember({{ $member->id }})"
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-xs font-medium rounded text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    Reject
                                </button>
                            @else
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-cloak
                                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-10">
                                        <div class="py-1">
                                            @if($isAdmin)
                                                @if($member->role === 'member')
                                                    <button wire:click="promoteMember({{ $member->id }}, 'moderator')" @click="open = false"
                                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                        Make Moderator
                                                    </button>
                                                @elseif($member->role === 'moderator')
                                                    <button wire:click="promoteMember({{ $member->id }}, 'member')" @click="open = false"
                                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                        Remove Moderator
                                                    </button>
                                                @endif
                                            @endif
                                            <button wire:click="removeMember({{ $member->id }})" @click="open = false"
                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                Remove from Group
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-8 text-center">
                    <p class="text-gray-500 dark:text-gray-400">
                        @if($tab === 'pending')
                            No pending member requests.
                        @else
                            No members found.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($members->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $members->links() }}
            </div>
        @endif
    </div>
</div>
