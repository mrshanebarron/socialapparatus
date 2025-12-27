<?php

namespace App\Livewire\Gifts;

use App\Models\VirtualGift;
use App\Models\SentGift;
use App\Models\CoinBalance;
use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public User $recipient;
    public $giftableType = null;
    public $giftableId = null;

    public bool $showSendModal = false;
    public ?VirtualGift $selectedGift = null;
    public string $message = '';
    public bool $isAnonymous = false;

    public function mount(User $recipient, $giftableType = null, $giftableId = null)
    {
        $this->recipient = $recipient;
        $this->giftableType = $giftableType;
        $this->giftableId = $giftableId;
    }

    public function selectGift($giftId)
    {
        $this->selectedGift = VirtualGift::findOrFail($giftId);
        $this->showSendModal = true;
    }

    public function closeSendModal()
    {
        $this->showSendModal = false;
        $this->selectedGift = null;
        $this->message = '';
        $this->isAnonymous = false;
    }

    public function sendGift()
    {
        if (!$this->selectedGift || !auth()->check()) return;

        $balance = CoinBalance::firstOrCreate(
            ['user_id' => auth()->id()],
            ['balance' => 0, 'lifetime_earned' => 0, 'lifetime_spent' => 0]
        );

        if ($balance->balance < $this->selectedGift->coin_cost) {
            session()->flash('error', 'Insufficient coins. Please purchase more coins.');
            return;
        }

        // Debit coins
        $balance->debit($this->selectedGift->coin_cost, "Sent {$this->selectedGift->name} gift");

        // Create sent gift record
        $sentGift = SentGift::create([
            'virtual_gift_id' => $this->selectedGift->id,
            'sender_id' => auth()->id(),
            'recipient_id' => $this->recipient->id,
            'giftable_type' => $this->giftableType,
            'giftable_id' => $this->giftableId,
            'message' => $this->message,
            'is_anonymous' => $this->isAnonymous,
            'coin_amount' => $this->selectedGift->coin_cost,
        ]);

        // Credit recipient if they have coin rewards enabled
        if ($this->recipient->profile?->accept_gifts) {
            $recipientBalance = CoinBalance::firstOrCreate(
                ['user_id' => $this->recipient->id],
                ['balance' => 0, 'lifetime_earned' => 0, 'lifetime_spent' => 0]
            );
            // Recipient gets a portion of the gift value
            $recipientCredit = (int) ($this->selectedGift->coin_cost * 0.7);
            $recipientBalance->credit($recipientCredit, "Received {$this->selectedGift->name} gift", $sentGift);
        }

        $this->closeSendModal();
        session()->flash('message', 'Gift sent successfully!');
    }

    public function render()
    {
        $gifts = VirtualGift::where('is_active', true)
            ->orderBy('category')
            ->orderBy('coin_cost')
            ->get()
            ->groupBy('category');

        $userBalance = auth()->check()
            ? CoinBalance::where('user_id', auth()->id())->first()?->balance ?? 0
            : 0;

        return view('livewire.gifts.index', [
            'gifts' => $gifts,
            'userBalance' => $userBalance,
        ]);
    }
}
