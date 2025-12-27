<div>
    <x-ui.card>
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-bold text-surface-900 dark:text-white">Notifications</h1>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                    Mark all as read
                </button>
            @endif
        </div>

        <!-- Filter Tabs -->
        <div class="flex space-x-1 mb-6 bg-surface-100 dark:bg-surface-800 p-1 rounded-xl">
            <button wire:click="setFilter('all')"
                    class="flex-1 py-2.5 px-4 text-sm font-medium rounded-lg transition-all {{ $filter === 'all' ? 'bg-white dark:bg-surface-700 text-primary-600 dark:text-primary-400 shadow-sm' : 'text-surface-600 dark:text-surface-400 hover:text-surface-900 dark:hover:text-white' }}">
                All
            </button>
            <button wire:click="setFilter('unread')"
                    class="flex-1 py-2.5 px-4 text-sm font-medium rounded-lg transition-all {{ $filter === 'unread' ? 'bg-white dark:bg-surface-700 text-primary-600 dark:text-primary-400 shadow-sm' : 'text-surface-600 dark:text-surface-400 hover:text-surface-900 dark:hover:text-white' }}">
                Unread
                @if($unreadCount > 0)
                    <span class="ml-1.5 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                        {{ $unreadCount }}
                    </span>
                @endif
            </button>
        </div>

        <!-- Notifications List -->
        <div class="divide-y divide-surface-200 dark:divide-surface-700 -mx-5">
            @forelse($notifications as $notification)
                <div wire:click="markAsRead({{ $notification->id }})"
                     class="p-4 hover:bg-surface-50 dark:hover:bg-surface-700/50 cursor-pointer transition-colors {{ !$notification->read_at ? 'bg-primary-50 dark:bg-primary-900/20' : '' }}">
                    <div class="flex items-start space-x-3">
                        @if($notification->fromUser)
                            <a wire:navigate href="{{ route('profile.view', $notification->fromUser) }}" class="flex-shrink-0">
                                <img src="{{ $notification->fromUser->profile_photo_url }}"
                                     alt="{{ $notification->fromUser->name }}"
                                     class="w-12 h-12 rounded-xl object-cover">
                            </a>
                        @else
                            <div class="w-12 h-12 rounded-xl bg-surface-200 dark:bg-surface-700 flex items-center justify-center flex-shrink-0">
                                <x-heroicon-o-bell class="w-6 h-6 text-surface-400" />
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-surface-900 dark:text-white">
                                @if($notification->fromUser)
                                    <a wire:navigate href="{{ route('profile.view', $notification->fromUser) }}" class="font-semibold hover:text-primary-600 dark:hover:text-primary-400">
                                        {{ $notification->fromUser->name }}
                                    </a>
                                @endif
                                {{ $notification->message }}
                            </p>
                            <p class="text-sm text-surface-500 dark:text-surface-400 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @if(!$notification->read_at)
                            <span class="w-2.5 h-2.5 bg-primary-500 rounded-full flex-shrink-0 mt-2"></span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <x-heroicon-o-bell class="w-16 h-16 mx-auto text-surface-300 dark:text-surface-600 mb-4" />
                    <h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">No notifications</h3>
                    <p class="text-surface-500 dark:text-surface-400">
                        @if($filter === 'unread')
                            You've read all your notifications!
                        @else
                            You don't have any notifications yet.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6 pt-4 border-t border-surface-200 dark:border-surface-700">
                {{ $notifications->links() }}
            </div>
        @endif
    </x-ui.card>
</div>
