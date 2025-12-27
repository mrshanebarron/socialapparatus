<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Login Alerts</h3>
        @if($alerts->where('is_read', false)->count() > 0)
            <button wire:click="markAllAsRead" class="text-sm text-indigo-600 hover:text-indigo-800">
                Mark all as read
            </button>
        @endif
    </div>

    @if($alerts->isEmpty())
        <p class="text-gray-500 dark:text-gray-400 text-sm">No login alerts.</p>
    @else
        <div class="space-y-3">
            @foreach($alerts as $alert)
                <div class="flex items-start justify-between p-3 {{ $alert->is_read ? 'bg-gray-50 dark:bg-gray-700' : 'bg-yellow-50 dark:bg-yellow-900/20' }} rounded-lg">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 mt-1">
                            @if($alert->type === 'new_device')
                                <svg class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            @elseif($alert->type === 'new_location')
                                <svg class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                            @else
                                <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $alert->message }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $alert->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @unless($alert->is_read)
                        <button wire:click="markAsRead({{ $alert->id }})" class="text-xs text-gray-500 hover:text-gray-700">
                            Mark read
                        </button>
                    @endunless
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $alerts->links() }}
        </div>
    @endif
</div>
