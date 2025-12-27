<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Coins</h1>
        @livewire('coins.purchase')
    </div>

    <!-- Balance Card -->
    <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-lg shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm">Current Balance</p>
                <p class="text-4xl font-bold">{{ number_format($balance?->balance ?? 0) }}</p>
                <p class="text-yellow-100 text-sm mt-1">coins</p>
            </div>
            <svg class="w-16 h-16 text-yellow-200/50" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path>
            </svg>
        </div>
        @if($balance)
            <div class="flex space-x-6 mt-4 pt-4 border-t border-yellow-300/30">
                <div>
                    <p class="text-yellow-100 text-xs">Lifetime Earned</p>
                    <p class="font-semibold">{{ number_format($balance->lifetime_earned) }}</p>
                </div>
                <div>
                    <p class="text-yellow-100 text-xs">Lifetime Spent</p>
                    <p class="font-semibold">{{ number_format($balance->lifetime_spent) }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="flex space-x-2 mb-4">
        <button wire:click="$set('filter', 'all')" class="px-4 py-2 rounded-lg text-sm {{ $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
            All
        </button>
        <button wire:click="$set('filter', 'credits')" class="px-4 py-2 rounded-lg text-sm {{ $filter === 'credits' ? 'bg-green-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
            Received
        </button>
        <button wire:click="$set('filter', 'debits')" class="px-4 py-2 rounded-lg text-sm {{ $filter === 'debits' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
            Spent
        </button>
    </div>

    <!-- Transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white">Transaction History</h3>
        </div>

        @if($transactions->isEmpty())
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No transactions yet</h3>
                <p class="text-gray-500 dark:text-gray-400">Your coin transactions will appear here.</p>
            </div>
        @else
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($transactions as $transaction)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $transaction->type === 'credit' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                                @if($transaction->type === 'credit')
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $transaction->description }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->created_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type === 'credit' ? '+' : '' }}{{ number_format($transaction->amount) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Balance: {{ number_format($transaction->balance_after) }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="px-6 py-4">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
