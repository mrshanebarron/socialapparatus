<?php

namespace App\Livewire\Connections;

use App\Models\User;
use Livewire\Component;

class FollowersList extends Component
{
    public ?User $user = null;
    public string $tab = 'followers';
    public string $search = '';

    public function mount(?User $user = null, string $tab = 'followers')
    {
        $this->user = $user ?? auth()->user();
        $this->tab = $tab;
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
        $this->search = '';
    }

    public function render()
    {
        $users = collect();

        if ($this->user) {
            if ($this->tab === 'followers') {
                $users = $this->user->followers();
            } else {
                $users = $this->user->following();
            }

            if ($this->search) {
                $search = strtolower($this->search);
                $users = $users->filter(function ($user) use ($search) {
                    return str_contains(strtolower($user->name), $search) ||
                           str_contains(strtolower($user->profile?->display_name ?? ''), $search) ||
                           str_contains(strtolower($user->profile?->username ?? ''), $search);
                });
            }
        }

        return view('livewire.connections.followers-list', [
            'users' => $users,
        ])->layout('layouts.app');
    }
}
