<?php

namespace App\Livewire\Pokes;

use App\Models\Poke;
use Livewire\Component;

class Index extends Component
{
    public function markAsSeen($pokeId)
    {
        $poke = auth()->user()->receivedPokes()->find($pokeId);
        if ($poke) {
            $poke->update(['is_seen' => true]);
        }
    }

    public function pokeBack($pokeId)
    {
        $poke = auth()->user()->receivedPokes()->find($pokeId);
        if ($poke) {
            auth()->user()->pokeBack($poke);
            $poke->update(['is_seen' => true]);
        }
    }

    public function render()
    {
        $receivedPokes = auth()->user()->receivedPokes()
            ->with('poker.profile')
            ->orderByDesc('created_at')
            ->take(20)
            ->get();

        $sentPokes = auth()->user()->sentPokes()
            ->with('poked.profile')
            ->orderByDesc('created_at')
            ->take(20)
            ->get();

        $unseenCount = auth()->user()->receivedPokes()
            ->where('is_seen', false)
            ->count();

        return view('livewire.pokes.index', [
            'receivedPokes' => $receivedPokes,
            'sentPokes' => $sentPokes,
            'unseenCount' => $unseenCount,
        ])->layout('layouts.app');
    }
}
