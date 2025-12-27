<div>
    <button wire:click="openModal" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium transition">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path>
        </svg>
        Buy Coins
    </button>

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-lg w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Buy Coins</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Current balance: {{ number_format($userBalance) }} coins</p>

                @if($packages->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No coin packages available.</p>
                @else
                    <div class="grid gap-4">
                        @foreach($packages as $package)
                            <button wire:click="selectPackage({{ $package->id }})" class="relative flex items-center justify-between p-4 border rounded-lg transition {{ $selectedPackage?->id === $package->id ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}">
                                @if($package->is_featured)
                                    <span class="absolute -top-2 left-4 px-2 py-0.5 bg-yellow-500 text-white text-xs rounded-full">Popular</span>
                                @endif
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $package->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ number_format($package->coins) }} coins
                                            @if($package->bonus_coins > 0)
                                                <span class="text-green-600">+{{ number_format($package->bonus_coins) }} bonus!</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">${{ number_format($package->price, 2) }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        ${{ number_format($package->price / $package->totalCoins() * 100, 2) }} per 100
                                    </p>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif

                @if($selectedPackage)
                    <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600 dark:text-gray-400">{{ $selectedPackage->name }}</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ number_format($selectedPackage->totalCoins()) }} coins</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold">
                            <span class="text-gray-900 dark:text-white">Total</span>
                            <span class="text-gray-900 dark:text-white">${{ number_format($selectedPackage->price, 2) }}</span>
                        </div>
                    </div>
                @endif

                <div class="flex space-x-3 mt-6">
                    <button wire:click="closeModal" class="flex-1 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg">Cancel</button>
                    <button wire:click="purchase" @if(!$selectedPackage) disabled @endif class="flex-1 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        Purchase
                    </button>
                </div>

                <p class="text-xs text-gray-500 dark:text-gray-400 text-center mt-4">
                    By purchasing, you agree to our Terms of Service.
                </p>
            </div>
        </div>
    @endif
</div>
