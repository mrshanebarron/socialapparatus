<?php

namespace App\Livewire\Connections;

use App\Models\User;
use Livewire\Component;

class FollowButton extends Component
{
    public User $user;
    public bool $isFollowing = false;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->updateFollowStatus();
    }

    public function updateFollowStatus()
    {
        $currentUser = auth()->user();

        if (!$currentUser || $currentUser->id === $this->user->id) {
            $this->isFollowing = false;
            return;
        }

        $this->isFollowing = $currentUser->isFollowing($this->user);
    }

    public function toggleFollow()
    {
        $currentUser = auth()->user();
        if (!$currentUser || $currentUser->id === $this->user->id) return;

        if ($this->isFollowing) {
            $currentUser->unfollow($this->user);
            $this->dispatch('unfollowed');
        } else {
            $currentUser->follow($this->user);
            $this->dispatch('followed');
        }

        $this->updateFollowStatus();
    }

    public function render()
    {
        return view('livewire.connections.follow-button');
    }
}
