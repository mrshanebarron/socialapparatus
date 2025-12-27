<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Badges</h3>

    @if($earnedBadges->isEmpty())
        <p class="text-gray-500 dark:text-gray-400 text-sm">No badges earned yet.</p>
    @else
        <div class="grid grid-cols-4 sm:grid-cols-6 gap-4">
            @foreach($earnedBadges as $badge)
                <div class="relative group">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl" style="background-color: {{ $badge->color ?? '#6366f1' }}20;">
                            {{ $badge->icon ?? '🏆' }}
                        </div>
                        <span class="text-xs text-gray-600 dark:text-gray-400 mt-1 text-center truncate w-full">{{ $badge->name }}</span>
                        @if($badge->pivot->displayed)
                            <span class="text-xs text-green-600">Displayed</span>
                        @endif
                    </div>

                    <!-- Tooltip -->
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
                        <p class="font-medium">{{ $badge->name }}</p>
                        <p class="text-gray-300">{{ $badge->description }}</p>
                        <p class="text-gray-400 mt-1">Earned: {{ $badge->pivot->earned_at->format('M j, Y') }}</p>
                    </div>

                    @if($isOwner)
                        <button wire:click="toggleDisplay({{ $badge->id }})" class="absolute top-0 right-0 opacity-0 group-hover:opacity-100 transition-opacity">
                            @if($badge->pivot->displayed)
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            @endif
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    @if($availableBadges->isNotEmpty() && $isOwner)
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Available Badges</h4>
            <div class="grid grid-cols-4 sm:grid-cols-6 gap-4">
                @foreach($availableBadges as $badge)
                    <div class="relative group opacity-50">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl bg-gray-200 dark:bg-gray-700">
                                {{ $badge->icon ?? '🏆' }}
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-500 mt-1 text-center truncate w-full">{{ $badge->name }}</span>
                        </div>

                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
                            <p class="font-medium">{{ $badge->name }}</p>
                            <p class="text-gray-300">{{ $badge->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
