<?php

namespace App\Livewire\Verification;

use App\Models\VerificationRequest;
use App\Models\VerifiedBadge;
use Livewire\Component;

class Status extends Component
{
    public $request = null;
    public $badge = null;

    public function mount()
    {
        $this->request = VerificationRequest::where('user_id', auth()->id())
            ->latest()
            ->first();

        $this->badge = VerifiedBadge::where('user_id', auth()->id())
            ->where('is_active', true)
            ->first();
    }

    public function render()
    {
        return view('livewire.verification.status');
    }
}
