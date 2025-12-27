<div>
    @if(session('message'))
        <div class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-4">
            {{ session('message') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Send a Gift</h3>
            @auth
                <div class="flex items-center space-x-2 text-sm">
                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path>
                    </svg>
                    <span class="font-medium text-gray-900 dark:text-white">{{ number_format($userBalance) }} coins</span>
                    <a href="{{ route('coins.purchase') }}" class="text-indigo-600 hover:text-indigo-800 text-xs">Buy more</a>
                </div>
            @endauth
        </div>

        @foreach($gifts as $category => $categoryGifts)
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 capitalize">{{ $category }}</h4>
                <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3">
                    @foreach($categoryGifts as $gift)
                        <button wire:click="selectGift({{ $gift->id }})" class="flex flex-col items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            @if($gift->image)
                                <img src="{{ Storage::url($gift->image) }}" alt="{{ $gift->name }}" class="w-10 h-10 object-contain">
                            @else
                                <span class="text-3xl">{{ $gift->emoji ?? '🎁' }}</span>
                            @endif
                            <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $gift->name }}</span>
                            <span class="text-xs text-yellow-600">{{ $gift->coin_cost }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Send Gift Modal -->
    @if($showSendModal && $selectedGift)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeSendModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-sm w-full p-6">
                <div class="text-center mb-4">
                    @if($selectedGift->image)
                        <img src="{{ Storage::url($selectedGift->image) }}" alt="{{ $selectedGift->name }}" class="w-20 h-20 mx-auto object-contain">
                    @else
                        <span class="text-6xl">{{ $selectedGift->emoji ?? '🎁' }}</span>
                    @endif
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mt-2">{{ $selectedGift->name }}</h3>
                    <p class="text-yellow-600 font-medium">{{ $selectedGift->coin_cost }} coins</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message (optional)</label>
                        <textarea wire:model="message" rows="2" maxlength="200" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm" placeholder="Add a message..."></textarea>
                    </div>

                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" wire:model="isAnonymous" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Send anonymously</span>
                    </label>

                    <div class="border-t dark:border-gray-700 pt-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-500 dark:text-gray-400">Your balance</span>
                            <span class="text-gray-900 dark:text-white">{{ number_format($userBalance) }} coins</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">After purchase</span>
                            <span class="{{ $userBalance >= $selectedGift->coin_cost ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($userBalance - $selectedGift->coin_cost) }} coins
                            </span>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <button wire:click="closeSendModal" class="flex-1 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg">Cancel</button>
                        <button wire:click="sendGift" @if($userBalance < $selectedGift->coin_cost) disabled @endif class="flex-1 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            Send Gift
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
