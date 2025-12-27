<?php

namespace App\Livewire\Connections;

use App\Models\User;
use Livewire\Component;

class FriendsList extends Component
{
    public ?User $user = null;
    public string $search = '';
    public bool $isOwnProfile = false;

    public function mount($user = null)
    {
        // If no user is passed, use the authenticated user
        // Note: Using mixed type to avoid Livewire route model binding issues
        if ($user === null || (is_string($user) && $user === '')) {
            $this->user = auth()->user();
            $this->isOwnProfile = true;
        } elseif ($user instanceof User) {
            $this->user = $user;
            $this->isOwnProfile = auth()->check() && $user->id === auth()->id();
        } elseif (is_numeric($user)) {
            $this->user = User::find($user);
            $this->isOwnProfile = auth()->check() && $this->user?->id === auth()->id();
        } else {
            $this->user = auth()->user();
            $this->isOwnProfile = true;
        }

        // Eager load profile
        if ($this->user) {
            $this->user->load('profile');
        }
    }

    public function render()
    {
        $friends = collect();

        if ($this->user) {
            $friends = $this->user->friends();

            if ($this->search) {
                $search = strtolower($this->search);
                $friends = $friends->filter(function ($friend) use ($search) {
                    return str_contains(strtolower($friend->name), $search) ||
                           str_contains(strtolower($friend->profile?->display_name ?? ''), $search) ||
                           str_contains(strtolower($friend->profile?->username ?? ''), $search);
                });
            }
        }

        // Determine if viewing own profile - check in render to handle Livewire hydration
        $isOwn = $this->user && auth()->check() && $this->user->id === auth()->id();
        $displayName = $this->user?->profile?->display_name ?: $this->user?->name;

        return view('livewire.connections.friends-list', [
            'friends' => $friends,
            'isOwn' => $isOwn,
            'displayName' => $displayName ?: 'User',
        ])->layout('layouts.app');
    }
}
