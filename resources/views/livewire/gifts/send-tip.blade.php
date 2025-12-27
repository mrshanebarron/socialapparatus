<div>
    @if($recipient->profile?->accept_tips)
        <button wire:click="openModal" class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm transition">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path>
            </svg>
            Tip
        </button>
    @endif

    @if(session('message'))
        <div class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mt-2">
            {{ session('message') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mt-2">
            {{ session('error') }}
        </div>
    @endif

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-sm w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 text-center">
                    Tip {{ $recipient->name }}
                </h3>

                <div class="space-y-4">
                    <!-- Quick Amounts -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($quickAmounts as $amt)
                                <button wire:click="setAmount({{ $amt }})" class="py-2 px-3 rounded-lg text-sm transition {{ $amount === $amt ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                    {{ $amt }} coins
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Custom Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Custom amount</label>
                        <input type="number" wire:model.live="amount" min="{{ $minimumTip }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        @if($minimumTip > 1)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimum tip: {{ $minimumTip }} coins</p>
                        @endif
                        @error('amount') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message (optional)</label>
                        <textarea wire:model="message" rows="2" maxlength="200" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm" placeholder="Say something nice..."></textarea>
                    </div>

                    <!-- Anonymous -->
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" wire:model="isAnonymous" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Send anonymously</span>
                    </label>

                    <!-- Balance Info -->
                    <div class="border-t dark:border-gray-700 pt-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-500 dark:text-gray-400">Your balance</span>
                            <span class="text-gray-900 dark:text-white">{{ number_format($userBalance) }} coins</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">After tip</span>
                            <span class="{{ $userBalance >= $amount ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($userBalance - $amount) }} coins
                            </span>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <button wire:click="closeModal" class="flex-1 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg">Cancel</button>
                        <button wire:click="sendTip" @if($userBalance < $amount) disabled @endif class="flex-1 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 disabled:opacity-50 disabled:cursor-not-allowed">
                            Send {{ $amount }} coins
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
