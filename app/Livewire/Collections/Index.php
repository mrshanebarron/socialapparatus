<?php

namespace App\Livewire\Collections;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public ?User $user = null;

    public function mount(?User $user = null)
    {
        $this->user = $user ?? auth()->user();
    }

    public function render()
    {
        $query = $this->user->collections()->withCount('items');

        if ($this->user->id !== auth()->id()) {
            $query->where('privacy', 'public');
        }

        $collections = $query->orderByDesc('updated_at')->paginate(12);

        return view('livewire.collections.index', [
            'collections' => $collections,
            'isOwner' => $this->user->id === auth()->id(),
        ])->layout('layouts.app');
    }
}
