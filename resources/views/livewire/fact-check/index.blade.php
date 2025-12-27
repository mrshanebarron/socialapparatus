<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Fact-Check Center</h1>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-4 mb-6">
        <div class="flex rounded-lg overflow-hidden border dark:border-gray-700">
            <button wire:click="$set('filter', 'all')" class="px-4 py-2 text-sm {{ $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">All</button>
            <button wire:click="$set('filter', 'disputed')" class="px-4 py-2 text-sm {{ $filter === 'disputed' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">Disputed</button>
            <button wire:click="$set('filter', 'recent')" class="px-4 py-2 text-sm {{ $filter === 'recent' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">Recent</button>
        </div>
        <select wire:model.live="labelId" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm">
            <option value="">All Labels</option>
            @foreach($labels as $label)
                <option value="{{ $label->id }}">{{ $label->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Labels Legend -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Fact-Check Labels</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($labels as $label)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm" style="background-color: {{ $label->color }}20; color: {{ $label->color }}">
                    {{ $label->icon }} {{ $label->name }}
                </span>
            @endforeach
        </div>
    </div>

    <!-- Verifications List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        @if($verifications->isEmpty())
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No fact-checks yet</h3>
                <p class="text-gray-500 dark:text-gray-400">Content verifications will appear here.</p>
            </div>
        @else
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($verifications as $verification)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" style="background-color: {{ $verification->label->color }}20; color: {{ $verification->label->color }}">
                                        {{ $verification->label->icon }} {{ $verification->label->name }}
                                    </span>
                                    @if($verification->is_disputed)
                                        <span class="inline-flex items-center px-2 py-1 bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 rounded-full text-xs">
                                            Disputed
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-900 dark:text-white mb-1">
                                    {{ Str::limit($verification->verifiable?->content ?? 'Content unavailable', 150) }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ Str::limit($verification->explanation, 100) }}
                                </p>
                                @if($verification->source_url)
                                    <a href="{{ $verification->source_url }}" target="_blank" class="text-xs text-indigo-600 hover:underline mt-1 inline-block">
                                        View Source
                                    </a>
                                @endif
                            </div>
                            <div class="text-right text-sm text-gray-500 dark:text-gray-400 ml-4">
                                <p>{{ $verification->verified_at?->diffForHumans() }}</p>
                                @if($verification->verifier)
                                    <p class="text-xs">by {{ $verification->verifier->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="px-6 py-4">
                {{ $verifications->links() }}
            </div>
        @endif
    </div>
</div>
