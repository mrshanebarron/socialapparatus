<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-surface-900 dark:text-white">Groups</h1>
        <a wire:navigate href="{{ route('groups.create') }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-xl text-white bg-primary-600 hover:bg-primary-700 transition-colors">
            <x-heroicon-o-plus class="-ml-1 mr-2 h-5 w-5" />
            Create Group
        </a>
    </div>

    <!-- Filters & Search -->
    <x-ui.card>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex space-x-2">
                <button wire:click="setFilter('all')" class="px-4 py-2 text-sm font-medium rounded-xl transition-colors {{ $filter === 'all' ? 'bg-primary-600 text-white' : 'bg-surface-100 dark:bg-surface-700 text-surface-700 dark:text-surface-300 hover:bg-surface-200 dark:hover:bg-surface-600' }}">
                    All Groups
                </button>
                @auth
                    <button wire:click="setFilter('my')" class="px-4 py-2 text-sm font-medium rounded-xl transition-colors {{ $filter === 'my' ? 'bg-primary-600 text-white' : 'bg-surface-100 dark:bg-surface-700 text-surface-700 dark:text-surface-300 hover:bg-surface-200 dark:hover:bg-surface-600' }}">
                        My Groups
                    </button>
                    <button wire:click="setFilter('owned')" class="px-4 py-2 text-sm font-medium rounded-xl transition-colors {{ $filter === 'owned' ? 'bg-primary-600 text-white' : 'bg-surface-100 dark:bg-surface-700 text-surface-700 dark:text-surface-300 hover:bg-surface-200 dark:hover:bg-surface-600' }}">
                        Groups I Own
                    </button>
                @endauth
            </div>
            <div class="w-full sm:w-64">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search groups..."
                       class="w-full rounded-xl border-surface-300 dark:border-surface-600 dark:bg-surface-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
        </div>
    </x-ui.card>

    <!-- Groups Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @forelse($groups as $group)
            <a wire:navigate href="{{ route('groups.show', $group) }}" class="block">
                <x-ui.card class="hover:shadow-lg transition group h-full">
                    <!-- Cover/Avatar -->
                    <div class="h-24 bg-gradient-to-r from-primary-500 to-accent-500 rounded-t-xl -mx-5 -mt-5 relative">
                        @if($group->cover_photo_url)
                            <img src="{{ $group->cover_photo_url }}" alt="" class="w-full h-full object-cover rounded-t-xl">
                        @endif
                        <div class="absolute -bottom-6 left-4">
                            <img src="{{ $group->avatar_url }}" alt="{{ $group->name }}" class="h-14 w-14 rounded-xl ring-4 ring-white dark:ring-surface-800 object-cover">
                        </div>
                    </div>

                    <div class="pt-8">
                        <h3 class="text-base font-semibold text-surface-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 truncate">
                            {{ $group->name }}
                        </h3>
                        <div class="flex items-center mt-1 space-x-3 text-sm text-surface-500 dark:text-surface-400">
                            <span class="flex items-center">
                                <x-heroicon-o-users class="w-4 h-4 mr-1" />
                                {{ number_format($group->members_count) }} members
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $group->privacy === 'public' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($group->privacy === 'private' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300') }}">
                                {{ ucfirst($group->privacy) }}
                            </span>
                        </div>
                        @if($group->description)
                            <p class="mt-2 text-sm text-surface-600 dark:text-surface-400 line-clamp-2">{{ $group->description }}</p>
                        @endif
                    </div>
                </x-ui.card>
            </a>
        @empty
            <div class="col-span-full">
                <x-ui.card>
                    <x-ui.empty-state
                        icon="user-group"
                        title="No groups found"
                        :description="match($filter) {
                            'my' => 'You haven\'t joined any groups yet.',
                            'owned' => 'You haven\'t created any groups yet.',
                            default => 'No groups match your search criteria.'
                        }"
                        action="{{ route('groups.create') }}"
                        actionText="Create a Group"
                    />
                </x-ui.card>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($groups->hasPages())
        <div class="mt-6">
            {{ $groups->links() }}
        </div>
    @endif
</div>
