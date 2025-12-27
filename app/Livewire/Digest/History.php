<?php

namespace App\Livewire\Digest;

use App\Models\SentDigest;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public ?SentDigest $viewingDigest = null;

    public function viewDigest($digestId)
    {
        $this->viewingDigest = SentDigest::with('items')->findOrFail($digestId);
    }

    public function closeDigest()
    {
        $this->viewingDigest = null;
    }

    public function render()
    {
        $digests = SentDigest::where('user_id', auth()->id())
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('livewire.digest.history', [
            'digests' => $digests,
        ])->layout('layouts.app');
    }
}
