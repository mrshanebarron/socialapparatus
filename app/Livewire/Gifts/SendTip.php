<?php

namespace App\Livewire\Gifts;

use App\Models\Tip;
use App\Models\CoinBalance;
use App\Models\User;
use Livewire\Component;

class SendTip extends Component
{
    public User $recipient;
    public $tippableType = null;
    public $tippableId = null;

    public bool $showModal = false;
    public int $amount = 10;
    public string $message = '';
    public bool $isAnonymous = false;

    public array $quickAmounts = [5, 10, 25, 50, 100, 500];

    public function mount(User $recipient, $tippableType = null, $tippableId = null)
    {
        $this->recipient = $recipient;
        $this->tippableType = $tippableType;
        $this->tippableId = $tippableId;
    }

    public function openModal()
    {
        if (!$this->recipient->profile?->accept_tips) {
            session()->flash('error', 'This user is not accepting tips.');
            return;
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->amount = 10;
        $this->message = '';
        $this->isAnonymous = false;
    }

    public function setAmount($amt)
    {
        $this->amount = $amt;
    }

    public function sendTip()
    {
        if (!auth()->check()) return;

        $minTip = $this->recipient->profile?->minimum_tip ?? 1;

        if ($this->amount < $minTip) {
            $this->addError('amount', "Minimum tip is {$minTip} coins.");
            return;
        }

        $balance = CoinBalance::firstOrCreate(
            ['user_id' => auth()->id()],
            ['balance' => 0, 'lifetime_earned' => 0, 'lifetime_spent' => 0]
        );

        if ($balance->balance < $this->amount) {
            session()->flash('error', 'Insufficient coins. Please purchase more coins.');
            return;
        }

        // Debit sender
        $balance->debit($this->amount, "Tip to {$this->recipient->name}");

        // Create tip record
        $tip = Tip::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $this->recipient->id,
            'tippable_type' => $this->tippableType,
            'tippable_id' => $this->tippableId,
            'amount' => $this->amount,
            'currency' => 'coins',
            'message' => $this->message,
            'is_anonymous' => $this->isAnonymous,
            'payment_status' => 'completed',
        ]);

        // Credit recipient (95% after platform fee)
        $recipientBalance = CoinBalance::firstOrCreate(
            ['user_id' => $this->recipient->id],
            ['balance' => 0, 'lifetime_earned' => 0, 'lifetime_spent' => 0]
        );
        $recipientCredit = (int) ($this->amount * 0.95);
        $recipientBalance->credit($recipientCredit, "Tip from " . ($this->isAnonymous ? 'Anonymous' : auth()->user()->name), $tip);

        $this->closeModal();
        session()->flash('message', 'Tip sent successfully!');
    }

    public function render()
    {
        $userBalance = auth()->check()
            ? CoinBalance::where('user_id', auth()->id())->first()?->balance ?? 0
            : 0;

        return view('livewire.gifts.send-tip', [
            'userBalance' => $userBalance,
            'minimumTip' => $this->recipient->profile?->minimum_tip ?? 1,
        ]);
    }
}
