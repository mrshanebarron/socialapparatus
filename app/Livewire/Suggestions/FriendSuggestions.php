<?php

namespace App\Livewire\Suggestions;

use Livewire\Component;

class FriendSuggestions extends Component
{
    public function sendRequest($userId)
    {
        if (!auth()->check()) return;

        $targetUser = \App\Models\User::find($userId);
        if (!$targetUser) return;

        auth()->user()->sendFriendRequest($targetUser);
    }

    public function render()
    {
        $suggestions = auth()->user()->getFriendSuggestions(5);

        return view('livewire.suggestions.friend-suggestions', [
            'suggestions' => $suggestions,
        ]);
    }
}
