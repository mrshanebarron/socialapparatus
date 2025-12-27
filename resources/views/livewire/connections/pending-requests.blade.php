<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Friend Requests</h2>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $pendingRequests->count() }} pending
                </span>
            </div>
        </div>

        <!-- Requests List -->
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($pendingRequests as $request)
                <div class="p-4 flex items-center justify-between">
                    <a href="{{ route('profile.view', $request->user) }}" class="flex items-center space-x-4">
                        <img class="h-12 w-12 rounded-full object-cover"
                             src="{{ $request->user->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($request->user->name) }}"
                             alt="{{ $request->user->name }}">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $request->user->profile?->display_name ?? $request->user->name }}
                            </p>
                            @if($request->user->profile?->username)
                                <p class="text-sm text-gray-500 dark:text-gray-400">@{{ $request->user->profile->username }}</p>
                            @endif
                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                {{ $request->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </a>
                    <div class="flex space-x-2">
                        <button wire:click="acceptRequest({{ $request->id }})" type="button"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Accept
                        </button>
                        <button wire:click="declineRequest({{ $request->id }})" type="button"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            Decline
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No pending requests</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        You don't have any friend requests at the moment.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>
