<?php

namespace App\Livewire\Connections;

use App\Models\User;
use Livewire\Component;

class ConnectionButton extends Component
{
    public User $user;
    public string $connectionStatus = 'none';

    public function mount(User $user)
    {
        $this->user = $user;
        $this->updateConnectionStatus();
    }

    public function updateConnectionStatus()
    {
        $currentUser = auth()->user();

        if (!$currentUser || $currentUser->id === $this->user->id) {
            $this->connectionStatus = 'self';
            return;
        }

        if ($currentUser->hasBlocked($this->user) || $this->user->hasBlocked($currentUser)) {
            $this->connectionStatus = 'blocked';
            return;
        }

        if ($currentUser->isFriendsWith($this->user)) {
            $this->connectionStatus = 'friends';
            return;
        }

        if ($currentUser->hasPendingFriendRequestTo($this->user)) {
            $this->connectionStatus = 'pending_sent';
            return;
        }

        if ($currentUser->hasPendingFriendRequestFrom($this->user)) {
            $this->connectionStatus = 'pending_received';
            return;
        }

        $this->connectionStatus = 'none';
    }

    public function sendFriendRequest()
    {
        $currentUser = auth()->user();
        if (!$currentUser) return;

        $currentUser->sendFriendRequest($this->user);
        $this->updateConnectionStatus();
        $this->dispatch('friend-request-sent');
    }

    public function acceptFriendRequest()
    {
        $currentUser = auth()->user();
        if (!$currentUser) return;

        $request = $currentUser->receivedConnections()
            ->friends()
            ->pending()
            ->where('user_id', $this->user->id)
            ->first();

        if ($request) {
            $request->accept();
            $this->updateConnectionStatus();
            $this->dispatch('friend-request-accepted');
        }
    }

    public function declineFriendRequest()
    {
        $currentUser = auth()->user();
        if (!$currentUser) return;

        $request = $currentUser->receivedConnections()
            ->friends()
            ->pending()
            ->where('user_id', $this->user->id)
            ->first();

        if ($request) {
            $request->decline();
            $this->updateConnectionStatus();
            $this->dispatch('friend-request-declined');
        }
    }

    public function cancelFriendRequest()
    {
        $currentUser = auth()->user();
        if (!$currentUser) return;

        $currentUser->sentConnections()
            ->friends()
            ->pending()
            ->where('target_id', $this->user->id)
            ->delete();

        $this->updateConnectionStatus();
        $this->dispatch('friend-request-cancelled');
    }

    public function unfriend()
    {
        $currentUser = auth()->user();
        if (!$currentUser) return;

        $currentUser->unfriend($this->user);
        $this->updateConnectionStatus();
        $this->dispatch('unfriended');
    }

    public function render()
    {
        return view('livewire.connections.connection-button');
    }
}
