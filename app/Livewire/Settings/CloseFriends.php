<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Livewire\Component;

class CloseFriends extends Component
{
    public string $search = '';
    public bool $showAddModal = false;

    public function addCloseFriend($userId)
    {
        $user = User::find($userId);
        if ($user && auth()->user()->isFriendsWith($user)) {
            auth()->user()->addCloseFriend($user);
        }
        $this->search = '';
        $this->showAddModal = false;
    }

    public function removeCloseFriend($userId)
    {
        $user = User::find($userId);
        if ($user) {
            auth()->user()->removeCloseFriend($user);
        }
    }

    public function render()
    {
        $closeFriends = auth()->user()->closeFriendUsers()->with('profile')->get();

        $searchResults = [];
        if (strlen($this->search) >= 2) {
            $closeFriendIds = auth()->user()->closeFriends()->pluck('friend_id');
            $friends = auth()->user()->friends();

            $searchResults = $friends->filter(function ($friend) use ($closeFriendIds) {
                return !$closeFriendIds->contains($friend->id) &&
                    (str_contains(strtolower($friend->name), strtolower($this->search)) ||
                     str_contains(strtolower($friend->profile?->display_name ?? ''), strtolower($this->search)));
            })->take(10);
        }

        return view('livewire.settings.close-friends', [
            'closeFriends' => $closeFriends,
            'searchResults' => $searchResults,
        ])->layout('layouts.app');
    }
}
