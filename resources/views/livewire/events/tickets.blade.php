<div class="space-y-6">
    @if(session('message'))
        <div class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Ticket Types -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tickets</h3>
            @if($isOrganizer)
                <button wire:click="openCreateModal" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                    + Add Ticket Type
                </button>
            @endif
        </div>

        @if($ticketTypes->isEmpty())
            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No tickets available for this event.</p>
        @else
            <div class="space-y-4">
                @foreach($ticketTypes as $ticketType)
                    <div class="border dark:border-gray-700 rounded-lg p-4 {{ $ticketType->isSoldOut() ? 'opacity-60' : '' }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $ticketType->name }}</h4>
                                @if($ticketType->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $ticketType->description }}</p>
                                @endif
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    {{ $ticketType->quantity_sold }}/{{ $ticketType->quantity_available }} sold
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold text-gray-900 dark:text-white">
                                    @if($ticketType->price > 0)
                                        ${{ number_format($ticketType->price, 2) }}
                                    @else
                                        Free
                                    @endif
                                </p>
                                @if(!$ticketType->isSoldOut() && !$isOrganizer)
                                    <button wire:click="openPurchaseModal({{ $ticketType->id }})"
                                            class="mt-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                                        Get Tickets
                                    </button>
                                @elseif($ticketType->isSoldOut())
                                    <span class="inline-block mt-2 px-3 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-full text-sm">
                                        Sold Out
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Waitlist -->
        @if($event->waitlist_enabled && $ticketTypes->every(fn($t) => $t->isSoldOut()))
            <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                @if($onWaitlist)
                    <p class="text-yellow-700 dark:text-yellow-300">You're on the waitlist! We'll notify you if spots open up.</p>
                @else
                    <button wire:click="joinWaitlist" class="w-full py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                        Join Waitlist
                    </button>
                @endif
            </div>
        @endif
    </div>

    <!-- User's Tickets -->
    @if($userTickets->isNotEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Your Tickets</h3>
            <div class="space-y-4">
                @foreach($userTickets as $ticket)
                    <div class="border dark:border-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $ticket->ticketType->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Code: {{ $ticket->ticket_code }}</p>
                            </div>
                            <div class="text-right">
                                @if($ticket->status === 'valid')
                                    <span class="inline-block px-3 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full text-sm">
                                        Valid
                                    </span>
                                @elseif($ticket->status === 'used')
                                    <span class="inline-block px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-full text-sm">
                                        Used
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- QR Code placeholder -->
                        <div class="mt-4 flex justify-center">
                            <div class="w-32 h-32 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                <span class="text-xs text-gray-500 dark:text-gray-400 text-center p-2">{{ $ticket->qr_code }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Organizer Check-in -->
    @if($isOrganizer)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Check-in</h3>
                <button wire:click="openCheckInModal" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                    Scan Ticket
                </button>
            </div>
        </div>
    @endif

    <!-- Create Ticket Type Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeCreateModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Create Ticket Type</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                        <input type="text" wire:model="ticketName" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea wire:model="ticketDescription" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price ($)</label>
                            <input type="number" wire:model="ticketPrice" min="0" step="0.01" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
                            <input type="number" wire:model="ticketQuantity" min="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max per person</label>
                        <input type="number" wire:model="maxPerUser" min="1" max="20" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    </div>
                    <div class="flex space-x-3">
                        <button wire:click="closeCreateModal" class="flex-1 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">Cancel</button>
                        <button wire:click="createTicketType" class="flex-1 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Create</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Purchase Modal -->
    @if($showPurchaseModal && $selectedTicketType)
        @php $ticketType = \App\Models\EventTicketType::find($selectedTicketType); @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closePurchaseModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Purchase Tickets</h3>
                <div class="space-y-4">
                    <p class="text-gray-600 dark:text-gray-400">{{ $ticketType->name }}</p>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
                        <select wire:model="purchaseQuantity" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            @for($i = 1; $i <= min($ticketType->max_per_user, $ticketType->quantity_available - $ticketType->quantity_sold); $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    @error('purchaseQuantity') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    <div class="border-t dark:border-gray-700 pt-4">
                        <div class="flex justify-between text-lg font-semibold text-gray-900 dark:text-white">
                            <span>Total</span>
                            <span>${{ number_format($ticketType->price * $purchaseQuantity, 2) }}</span>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <button wire:click="closePurchaseModal" class="flex-1 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg">Cancel</button>
                        <button wire:click="purchaseTickets" class="flex-1 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Purchase</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Check-in Modal -->
    @if($showCheckInModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeCheckInModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Check-in Attendee</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ticket Code or QR</label>
                        <input type="text" wire:model="ticketCode" wire:keydown.enter="scanTicket" placeholder="Enter code or scan QR"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    </div>
                    <button wire:click="scanTicket" class="w-full py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Scan</button>

                    @if($checkInMessage)
                        <p class="text-center {{ str_contains($checkInMessage, 'Successfully') ? 'text-green-600' : 'text-red-600' }}">
                            {{ $checkInMessage }}
                        </p>
                    @endif

                    @if($scannedTicket && $scannedTicket->status === 'valid')
                        <div class="border dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $scannedTicket->user->profile_photo_url }}" class="w-12 h-12 rounded-full">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $scannedTicket->user->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $scannedTicket->ticketType->name }}</p>
                                </div>
                            </div>
                            <button wire:click="confirmCheckIn" class="w-full mt-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                Confirm Check-in
                            </button>
                        </div>
                    @endif

                    <button wire:click="closeCheckInModal" class="w-full py-2 text-gray-600 dark:text-gray-400">Close</button>
                </div>
            </div>
        </div>
    @endif
</div>
