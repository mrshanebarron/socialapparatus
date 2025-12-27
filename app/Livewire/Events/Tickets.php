<?php

namespace App\Livewire\Events;

use App\Models\Event;
use App\Models\EventTicketType;
use App\Models\EventTicket;
use Livewire\Component;
use Illuminate\Support\Str;

class Tickets extends Component
{
    public Event $event;
    public bool $showCreateModal = false;
    public bool $showPurchaseModal = false;
    public bool $showCheckInModal = false;

    // Ticket type creation
    public string $ticketName = '';
    public string $ticketDescription = '';
    public float $ticketPrice = 0;
    public int $ticketQuantity = 100;
    public ?string $ticketSaleStart = null;
    public ?string $ticketSaleEnd = null;
    public int $maxPerUser = 5;

    // Purchase
    public ?int $selectedTicketType = null;
    public int $purchaseQuantity = 1;

    // Check-in
    public string $ticketCode = '';
    public ?EventTicket $scannedTicket = null;
    public string $checkInMessage = '';

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetTicketFields();
    }

    protected function resetTicketFields()
    {
        $this->ticketName = '';
        $this->ticketDescription = '';
        $this->ticketPrice = 0;
        $this->ticketQuantity = 100;
        $this->ticketSaleStart = null;
        $this->ticketSaleEnd = null;
        $this->maxPerUser = 5;
    }

    public function createTicketType()
    {
        $this->validate([
            'ticketName' => 'required|min:2|max:100',
            'ticketDescription' => 'nullable|max:500',
            'ticketPrice' => 'required|numeric|min:0',
            'ticketQuantity' => 'required|integer|min:1',
            'maxPerUser' => 'required|integer|min:1|max:20',
        ]);

        $this->event->ticketTypes()->create([
            'name' => $this->ticketName,
            'description' => $this->ticketDescription,
            'price' => $this->ticketPrice,
            'currency' => 'USD',
            'quantity_available' => $this->ticketQuantity,
            'max_per_user' => $this->maxPerUser,
            'sale_start' => $this->ticketSaleStart,
            'sale_end' => $this->ticketSaleEnd,
        ]);

        $this->closeCreateModal();
    }

    public function openPurchaseModal($ticketTypeId)
    {
        $this->selectedTicketType = $ticketTypeId;
        $this->purchaseQuantity = 1;
        $this->showPurchaseModal = true;
    }

    public function closePurchaseModal()
    {
        $this->showPurchaseModal = false;
        $this->selectedTicketType = null;
    }

    public function purchaseTickets()
    {
        $ticketType = EventTicketType::findOrFail($this->selectedTicketType);

        // Validation
        $existingCount = $ticketType->tickets()->where('user_id', auth()->id())->count();
        $remaining = $ticketType->quantity_available - $ticketType->quantity_sold;

        if ($this->purchaseQuantity > $remaining) {
            $this->addError('purchaseQuantity', 'Not enough tickets available.');
            return;
        }

        if ($existingCount + $this->purchaseQuantity > $ticketType->max_per_user) {
            $this->addError('purchaseQuantity', "Maximum {$ticketType->max_per_user} tickets per person.");
            return;
        }

        // Create tickets
        for ($i = 0; $i < $this->purchaseQuantity; $i++) {
            EventTicket::create([
                'event_ticket_type_id' => $ticketType->id,
                'user_id' => auth()->id(),
                'ticket_code' => strtoupper(Str::random(8)),
                'qr_code' => Str::uuid(),
                'status' => 'valid',
                'price_paid' => $ticketType->price,
                'purchased_at' => now(),
            ]);
        }

        $ticketType->increment('quantity_sold', $this->purchaseQuantity);

        $this->closePurchaseModal();
        session()->flash('message', "Successfully purchased {$this->purchaseQuantity} ticket(s)!");
    }

    public function openCheckInModal()
    {
        $this->showCheckInModal = true;
        $this->ticketCode = '';
        $this->scannedTicket = null;
        $this->checkInMessage = '';
    }

    public function closeCheckInModal()
    {
        $this->showCheckInModal = false;
    }

    public function scanTicket()
    {
        $this->scannedTicket = EventTicket::where('ticket_code', strtoupper($this->ticketCode))
            ->orWhere('qr_code', $this->ticketCode)
            ->whereHas('ticketType', fn($q) => $q->where('event_id', $this->event->id))
            ->with(['user', 'ticketType'])
            ->first();

        if (!$this->scannedTicket) {
            $this->checkInMessage = 'Ticket not found.';
            return;
        }

        if ($this->scannedTicket->status === 'used') {
            $this->checkInMessage = 'Ticket already used at ' . $this->scannedTicket->checked_in_at->format('M d, Y g:i A');
            return;
        }

        if ($this->scannedTicket->status === 'cancelled') {
            $this->checkInMessage = 'This ticket has been cancelled.';
            return;
        }

        $this->checkInMessage = '';
    }

    public function confirmCheckIn()
    {
        if (!$this->scannedTicket || $this->scannedTicket->status !== 'valid') {
            return;
        }

        $this->scannedTicket->update([
            'status' => 'used',
            'checked_in_at' => now(),
        ]);

        $this->checkInMessage = 'Successfully checked in!';
        $this->scannedTicket->refresh();
    }

    public function joinWaitlist()
    {
        $exists = $this->event->waitlist()->where('user_id', auth()->id())->exists();

        if ($exists) {
            session()->flash('message', 'You are already on the waitlist.');
            return;
        }

        $this->event->waitlist()->create([
            'user_id' => auth()->id(),
            'position' => $this->event->waitlist()->count() + 1,
        ]);

        session()->flash('message', 'Added to waitlist!');
    }

    public function render()
    {
        $ticketTypes = $this->event->ticketTypes()->withCount('tickets')->get();
        $userTickets = auth()->check()
            ? EventTicket::whereHas('ticketType', fn($q) => $q->where('event_id', $this->event->id))
                ->where('user_id', auth()->id())
                ->with('ticketType')
                ->get()
            : collect();
        $isOrganizer = auth()->id() === $this->event->user_id;
        $onWaitlist = auth()->check() && $this->event->waitlist()->where('user_id', auth()->id())->exists();

        return view('livewire.events.tickets', [
            'ticketTypes' => $ticketTypes,
            'userTickets' => $userTickets,
            'isOrganizer' => $isOrganizer,
            'onWaitlist' => $onWaitlist,
        ]);
    }
}
