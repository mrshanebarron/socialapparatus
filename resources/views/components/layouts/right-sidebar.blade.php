@auth
<div class="lg:col-span-3 space-y-6">
    <!-- Friend Requests -->
    @php $pendingRequests = auth()->user()->pendingFriendRequests(); @endphp
    @if($pendingRequests->count() > 0)
        <x-ui.card>
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-semibold text-surface-900 dark:text-surface-100">Friend Requests</h4>
                <span class="text-xs font-bold bg-red-500 text-white px-2 py-0.5 rounded-full">{{ $pendingRequests->count() }}</span>
            </div>
            <div class="space-y-4">
                @foreach($pendingRequests->take(3) as $request)
                    <div class="flex items-start gap-3">
                        <img src="{{ $request->sender->profile_photo_url }}" alt="{{ $request->sender->name }}" class="w-12 h-12 rounded-xl object-cover">
                        <div class="flex-1 min-w-0">
                            <a wire:navigate href="{{ route('profile.view', $request->sender) }}" class="font-medium text-surface-900 dark:text-surface-100 hover:text-primary-600 dark:hover:text-primary-400">
                                {{ $request->sender->name }}
                            </a>
                            <p class="text-xs text-surface-500 mb-2">{{ $request->sender->mutual_friends_count ?? 0 }} mutual friends</p>
                            <div class="flex gap-2">
                                <button wire:click="acceptFriendRequest({{ $request->id }})" class="flex-1 px-3 py-1.5 text-xs font-medium bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors">
                                    Accept
                                </button>
                                <button wire:click="declineFriendRequest({{ $request->id }})" class="flex-1 px-3 py-1.5 text-xs font-medium bg-surface-200 dark:bg-surface-700 text-surface-700 dark:text-surface-300 rounded-lg hover:bg-surface-300 dark:hover:bg-surface-600 transition-colors">
                                    Decline
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($pendingRequests->count() > 3)
                <a wire:navigate href="{{ route('friends.requests') }}" class="block w-full text-center py-2.5 mt-4 text-sm font-medium text-primary-600 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-xl transition-colors">
                    See All Requests
                </a>
            @endif
        </x-ui.card>
    @endif

    <!-- People You May Know -->
    <x-ui.card>
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-semibold text-surface-900 dark:text-surface-100">People You May Know</h4>
        </div>
        @livewire('suggestions.friend-suggestions')
    </x-ui.card>

    <!-- Upcoming Events -->
    <x-ui.card>
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-semibold text-surface-900 dark:text-surface-100">Upcoming Events</h4>
            <a wire:navigate href="{{ route('events.index') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">See all</a>
        </div>
        <p class="text-sm text-surface-500 dark:text-surface-400 text-center py-4">No upcoming events</p>
    </x-ui.card>

    <!-- Trending Topics -->
    <x-ui.card>
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-semibold text-surface-900 dark:text-surface-100">Trending</h4>
        </div>
        @livewire('suggestions.trending')
    </x-ui.card>

    <!-- Footer Links -->
    <div class="text-xs text-surface-500 space-y-2 px-2">
        <div class="flex flex-wrap gap-x-2 gap-y-1">
            <a wire:navigate href="#" class="hover:underline">About</a>
            <a wire:navigate href="#" class="hover:underline">Privacy</a>
            <a wire:navigate href="#" class="hover:underline">Terms</a>
            <a wire:navigate href="#" class="hover:underline">Help</a>
        </div>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
    </div>
</div>
@endauth
