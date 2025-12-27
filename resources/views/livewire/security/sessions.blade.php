<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Active Sessions</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your active login sessions across devices</p>
                    </div>
                    @if($sessions->count() > 1)
                        <button wire:click="terminateAllOthers" wire:confirm="Are you sure you want to log out of all other sessions?" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                            Log Out All Others
                        </button>
                    @endif
                </div>

                <div class="space-y-4">
                    @foreach($sessions as $session)
                        <div class="flex items-center justify-between p-4 {{ $session->is_current ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-gray-50 dark:bg-gray-700' }} rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if($session->device_type === 'mobile')
                                        <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    @elseif($session->device_type === 'tablet')
                                        <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    @else
                                        <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $session->browser ?? 'Unknown Browser' }} on {{ $session->os ?? 'Unknown OS' }}
                                        @if($session->is_current)
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                This device
                                            </span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $session->ip_address }}
                                        @if($session->city || $session->country)
                                            &bull; {{ $session->city }}{{ $session->city && $session->country ? ', ' : '' }}{{ $session->country }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                        Last active: {{ $session->last_active_at?->diffForHumans() ?? 'Unknown' }}
                                    </p>
                                </div>
                            </div>
                            @unless($session->is_current)
                                <button wire:click="terminateSession({{ $session->id }})" wire:confirm="Are you sure you want to log out this session?" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Log Out
                                </button>
                            @endunless
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
