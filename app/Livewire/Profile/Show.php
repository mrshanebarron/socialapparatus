<?php

namespace App\Livewire\Profile;

use App\Models\Profile;
use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public Profile $profile;
    public bool $isOwner = false;
    public bool $canView = true;
    public string $activeTab = 'posts';

    public function mount(User $user)
    {
        $this->profile = $user->getOrCreateProfile();
        $this->isOwner = auth()->id() === $user->id;
        $this->canView = $this->profile->isVisibleTo(auth()->user());

        // Record profile view
        if ($this->canView && !$this->isOwner) {
            $this->profile->recordView(
                auth()->user(),
                request()->ip(),
                request()->userAgent()
            );
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $data = [];

        if ($this->canView) {
            // Load posts for posts tab
            if ($this->activeTab === 'posts') {
                $data['posts'] = $this->profile->user->posts()
                    ->with(['user.profile', 'topLevelComments.user.profile'])
                    ->latest()
                    ->take(10)
                    ->get();
            }

            // Load photos for photos tab
            if ($this->activeTab === 'photos' || $this->activeTab === 'posts') {
                $data['photos'] = $this->profile->user->media()
                    ->where('type', 'image')
                    ->latest()
                    ->take(9)
                    ->get();
            }

            // Load friends
            $data['friends'] = $this->profile->user->friends()->take(9);
        }

        return view('livewire.profile.show', $data)
            ->layout('layouts.app');
    }
}
