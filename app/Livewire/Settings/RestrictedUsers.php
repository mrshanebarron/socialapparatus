<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Livewire\Component;

class RestrictedUsers extends Component
{
    public string $search = '';
    public bool $showAddModal = false;
    public ?string $restrictReason = null;

    public function restrictUser($userId)
    {
        $user = User::find($userId);
        if ($user && $user->id !== auth()->id()) {
            auth()->user()->restrict($user, $this->restrictReason);
        }
        $this->search = '';
        $this->restrictReason = null;
        $this->showAddModal = false;
    }

    public function unrestrictUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            auth()->user()->unrestrict($user);
        }
    }

    public function render()
    {
        $restrictedUsers = auth()->user()->restrictedUsers()
            ->with('restrictedUser.profile')
            ->get();

        $searchResults = [];
        if (strlen($this->search) >= 2) {
            $restrictedIds = auth()->user()->restrictedUsers()->pluck('restricted_user_id');

            $searchResults = User::where('id', '!=', auth()->id())
                ->whereNotIn('id', $restrictedIds)
                ->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhereHas('profile', function ($q) {
                            $q->where('display_name', 'like', "%{$this->search}%")
                                ->orWhere('username', 'like', "%{$this->search}%");
                        });
                })
                ->with('profile')
                ->limit(10)
                ->get();
        }

        return view('livewire.settings.restricted-users', [
            'restrictedUsers' => $restrictedUsers,
            'searchResults' => $searchResults,
        ])->layout('layouts.app');
    }
}
