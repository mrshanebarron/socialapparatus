<?php

namespace App\Livewire\Profile;

use App\Models\Badge;
use App\Models\User;
use Livewire\Component;

class Badges extends Component
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function toggleDisplay($badgeId)
    {
        if ($this->user->id !== auth()->id()) return;

        $userBadge = $this->user->badges()->where('badge_id', $badgeId)->first();
        if ($userBadge) {
            $this->user->badges()->updateExistingPivot($badgeId, [
                'displayed' => !$userBadge->pivot->displayed
            ]);
        }
    }

    public function render()
    {
        $earnedBadges = $this->user->badges()->get();

        $availableBadges = Badge::active()
            ->where('is_secret', false)
            ->whereNotIn('id', $earnedBadges->pluck('id'))
            ->get();

        return view('livewire.profile.badges', [
            'earnedBadges' => $earnedBadges,
            'availableBadges' => $availableBadges,
            'isOwner' => $this->user->id === auth()->id(),
        ]);
    }
}
