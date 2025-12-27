<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Digest History</h1>
        <a href="{{ route('digest.preferences') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
            Preferences
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        @if($digests->isEmpty())
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No digests yet</h3>
                <p class="text-gray-500 dark:text-gray-400">Your weekly digests will appear here.</p>
            </div>
        @else
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($digests as $digest)
                    <div wire:click="viewDigest({{ $digest->id }})" class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">
                                    {{ ucfirst($digest->digest_type) }} Digest - {{ $digest->period_start->format('M d') }} to {{ $digest->period_end->format('M d, Y') }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $digest->items_count }} items
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $digest->sent_at->format('M d, Y') }}</p>
                                @if($digest->opened_at)
                                    <span class="text-xs text-green-600">Opened</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="px-6 py-4">
                {{ $digests->links() }}
            </div>
        @endif
    </div>

    <!-- View Digest Modal -->
    @if($viewingDigest)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeDigest"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[80vh] overflow-y-auto">
                <div class="sticky top-0 bg-white dark:bg-gray-800 border-b dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ ucfirst($viewingDigest->digest_type) }} Digest
                    </h3>
                    <button wire:click="closeDigest" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        {{ $viewingDigest->period_start->format('M d') }} - {{ $viewingDigest->period_end->format('M d, Y') }}
                    </p>

                    @if($viewingDigest->items->isEmpty())
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">No items in this digest.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($viewingDigest->items->groupBy('item_type') as $type => $items)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 capitalize">{{ $type }}</h4>
                                    <div class="space-y-2">
                                        @foreach($items as $item)
                                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                                <p class="text-sm text-gray-900 dark:text-white">{{ $item->title }}</p>
                                                @if($item->summary)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $item->summary }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
