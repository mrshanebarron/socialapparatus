<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Pokes</h2>
                        @if($unseenCount > 0)
                            <p class="text-sm text-indigo-600 dark:text-indigo-400">{{ $unseenCount }} new {{ Str::plural('poke', $unseenCount) }}</p>
                        @endif
                    </div>
                </div>

                <!-- Received Pokes -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Received Pokes</h3>

                    @if($receivedPokes->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No one has poked you yet.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($receivedPokes as $poke)
                                <div class="flex items-center justify-between p-4 {{ $poke->is_seen ? 'bg-gray-50 dark:bg-gray-700' : 'bg-indigo-50 dark:bg-indigo-900/20' }} rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('profile.view', $poke->poker) }}">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $poke->poker->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($poke->poker->name) }}" alt="">
                                        </a>
                                        <div>
                                            <a href="{{ route('profile.view', $poke->poker) }}" class="font-medium text-gray-900 dark:text-white hover:underline">
                                                {{ $poke->poker->profile?->display_name ?? $poke->poker->name }}
                                            </a>
                                            <span class="text-gray-500 dark:text-gray-400"> poked you</span>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $poke->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($poke->poked_back_at)
                                            <span class="text-xs text-green-600 dark:text-green-400">Poked back {{ $poke->poked_back_at->diffForHumans() }}</span>
                                        @else
                                            <button wire:click="pokeBack({{ $poke->id }})" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                                                <span class="mr-1">👆</span> Poke Back
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Sent Pokes -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Sent Pokes</h3>

                    @if($sentPokes->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-sm">You haven't poked anyone yet.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($sentPokes as $poke)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('profile.view', $poke->poked) }}">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $poke->poked->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($poke->poked->name) }}" alt="">
                                        </a>
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">You poked </span>
                                            <a href="{{ route('profile.view', $poke->poked) }}" class="font-medium text-gray-900 dark:text-white hover:underline">
                                                {{ $poke->poked->profile?->display_name ?? $poke->poked->name }}
                                            </a>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $poke->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        @if($poke->poked_back_at)
                                            <span class="text-xs text-green-600 dark:text-green-400">Poked you back!</span>
                                        @elseif($poke->is_seen)
                                            <span class="text-xs text-gray-400 dark:text-gray-500">Seen</span>
                                        @else
                                            <span class="text-xs text-gray-400 dark:text-gray-500">Pending</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
