<?php

namespace App\Livewire\Coins;

use App\Models\CoinPackage;
use App\Models\CoinBalance;
use Livewire\Component;

class Purchase extends Component
{
    public bool $showModal = false;
    public ?CoinPackage $selectedPackage = null;

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedPackage = null;
    }

    public function selectPackage($packageId)
    {
        $this->selectedPackage = CoinPackage::findOrFail($packageId);
    }

    public function purchase()
    {
        if (!$this->selectedPackage) return;

        // In a real app, this would integrate with Stripe/PayPal
        // For now, we'll simulate a successful purchase

        $balance = CoinBalance::firstOrCreate(
            ['user_id' => auth()->id()],
            ['balance' => 0, 'lifetime_earned' => 0, 'lifetime_spent' => 0]
        );

        $totalCoins = $this->selectedPackage->totalCoins();
        $balance->credit(
            $totalCoins,
            "Purchased {$this->selectedPackage->name} package",
            $this->selectedPackage
        );

        session()->flash('message', "Successfully purchased {$totalCoins} coins!");
        $this->closeModal();
    }

    public function render()
    {
        $packages = CoinPackage::active()->orderBy('price')->get();

        $userBalance = auth()->check()
            ? CoinBalance::where('user_id', auth()->id())->first()?->balance ?? 0
            : 0;

        return view('livewire.coins.purchase', [
            'packages' => $packages,
            'userBalance' => $userBalance,
        ]);
    }
}
