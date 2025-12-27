<?php

namespace App\Livewire\Pokes;

use App\Models\User;
use Livewire\Component;

class PokeButton extends Component
{
    public User $user;
    public bool $justPoked = false;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function poke()
    {
        if ($this->user->id === auth()->id()) return;

        $poke = auth()->user()->poke($this->user);
        if ($poke) {
            $this->justPoked = true;
        }
    }

    public function render()
    {
        return view('livewire.pokes.poke-button');
    }
}
