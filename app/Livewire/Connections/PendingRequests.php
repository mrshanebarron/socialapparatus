<?php

namespace App\Livewire\Connections;

use Livewire\Component;

class PendingRequests extends Component
{
    public function acceptRequest($connectionId)
    {
        $request = auth()->user()->receivedConnections()
            ->friends()
            ->pending()
            ->where('id', $connectionId)
            ->first();

        if ($request) {
            $request->accept();
            $this->dispatch('friend-request-accepted');
        }
    }

    public function declineRequest($connectionId)
    {
        $request = auth()->user()->receivedConnections()
            ->friends()
            ->pending()
            ->where('id', $connectionId)
            ->first();

        if ($request) {
            $request->decline();
            $this->dispatch('friend-request-declined');
        }
    }

    public function render()
    {
        return view('livewire.connections.pending-requests', [
            'pendingRequests' => auth()->user()->pendingFriendRequests(),
        ])->layout('layouts.app');
    }
}
