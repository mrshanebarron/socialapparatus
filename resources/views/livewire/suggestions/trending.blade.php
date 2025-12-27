<div>
    @if($trends->isEmpty())
        <p class="text-sm text-surface-500 dark:text-surface-400">No trending topics right now.</p>
    @else
        <div class="space-y-2">
            @foreach($trends as $index => $trend)
                <a wire:navigate href="{{ $trend->type === 'hashtag' ? route('hashtags.show', ltrim($trend->name, '#')) : route('search', ['q' => $trend->name]) }}" class="block hover:bg-surface-50 dark:hover:bg-surface-700/50 -mx-2 px-2 py-2 rounded-lg transition-colors">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-xs text-surface-400 dark:text-surface-500">{{ $index + 1 }} &bull; {{ ucfirst($trend->type) }}</span>
                            <p class="font-medium text-surface-900 dark:text-white">{{ $trend->name }}</p>
                            <p class="text-xs text-surface-500 dark:text-surface-400">{{ number_format($trend->daily_count) }} posts</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
