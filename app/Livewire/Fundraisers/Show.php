<?php

namespace App\Livewire\Fundraisers;

use App\Models\Fundraiser;
use App\Models\FundraiserDonation;
use Livewire\Component;

class Show extends Component
{
    public Fundraiser $fundraiser;
    public bool $showDonateModal = false;
    public float $donationAmount = 25;
    public string $donorName = '';
    public string $donationMessage = '';
    public bool $isAnonymous = false;
    public bool $showShareModal = false;

    public array $suggestedAmounts = [10, 25, 50, 100, 250, 500];

    public function mount(Fundraiser $fundraiser)
    {
        $this->fundraiser = $fundraiser;
        if (auth()->check()) {
            $this->donorName = auth()->user()->name;
        }
    }

    public function setAmount(float $amount)
    {
        $this->donationAmount = $amount;
    }

    public function donate()
    {
        if (!auth()->check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        $this->validate([
            'donationAmount' => 'required|numeric|min:1',
            'donorName' => 'required|string|max:255',
            'donationMessage' => 'nullable|string|max:500',
        ]);

        // Create the donation (in real app, this would integrate with Stripe/PayPal)
        $donation = FundraiserDonation::create([
            'fundraiser_id' => $this->fundraiser->id,
            'user_id' => auth()->id(),
            'donor_name' => $this->donorName,
            'amount' => $this->donationAmount,
            'currency' => 'USD',
            'message' => $this->donationMessage ?: null,
            'is_anonymous' => $this->isAnonymous,
            'payment_provider' => 'demo', // Would be 'stripe' or 'paypal' in production
            'payment_id' => 'demo_' . uniqid(),
            'status' => 'completed', // Would be 'pending' until payment confirmed
        ]);

        // Mark as completed (in real app, this happens after payment webhook)
        $donation->markAsCompleted();

        $this->showDonateModal = false;
        $this->donationAmount = 25;
        $this->donationMessage = '';
        $this->isAnonymous = false;

        $this->fundraiser->refresh();

        session()->flash('success', 'Thank you for your donation!');
    }

    public function share()
    {
        $this->fundraiser->increment('shares_count');
        $this->showShareModal = true;
    }

    public function render()
    {
        $recentDonations = $this->fundraiser->donations()
            ->completed()
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('livewire.fundraisers.show', [
            'recentDonations' => $recentDonations,
            'categories' => Fundraiser::CATEGORIES,
        ]);
    }
}
