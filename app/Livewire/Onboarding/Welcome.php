<?php

namespace App\Livewire\Onboarding;

use Livewire\Component;
use Livewire\WithFileUploads;

class Welcome extends Component
{
    use WithFileUploads;

    public int $step = 1;
    public string $displayName = '';
    public string $headline = '';
    public string $bio = '';
    public string $location = '';
    public $avatar = null;
    public array $selectedInterests = [];
    public array $suggestedUsers = [];

    public array $interests = [
        'Technology', 'Music', 'Sports', 'Art', 'Travel',
        'Food', 'Fashion', 'Gaming', 'Movies', 'Books',
        'Photography', 'Fitness', 'Science', 'Business', 'Nature'
    ];

    public function mount()
    {
        $user = auth()->user();
        $profile = $user->profile;

        $this->displayName = $profile?->display_name ?? $user->name;
        $this->headline = $profile?->headline ?? '';
        $this->bio = $profile?->bio ?? '';
        $this->location = $profile?->location ?? '';
    }

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'displayName' => 'required|min:2|max:100',
            ]);
        }

        if ($this->step === 3) {
            // Load suggested users for step 4
            $this->loadSuggestedUsers();
        }

        $this->step = min($this->step + 1, 5);
    }

    public function prevStep()
    {
        $this->step = max($this->step - 1, 1);
    }

    public function toggleInterest($interest)
    {
        if (in_array($interest, $this->selectedInterests)) {
            $this->selectedInterests = array_values(array_diff($this->selectedInterests, [$interest]));
        } else {
            $this->selectedInterests[] = $interest;
        }
    }

    public function loadSuggestedUsers()
    {
        $this->suggestedUsers = \App\Models\User::where('id', '!=', auth()->id())
            ->inRandomOrder()
            ->limit(6)
            ->with('profile')
            ->get()
            ->toArray();
    }

    public function sendFriendRequest($userId)
    {
        $targetUser = \App\Models\User::find($userId);
        if ($targetUser) {
            auth()->user()->sendFriendRequest($targetUser);
        }
    }

    public function complete()
    {
        $user = auth()->user();
        $profile = $user->getOrCreateProfile();

        // Update profile
        $profile->update([
            'display_name' => $this->displayName,
            'headline' => $this->headline,
            'bio' => $this->bio,
            'location' => $this->location,
            'interests' => $this->selectedInterests,
            'onboarding_completed' => true,
        ]);

        // Handle avatar upload
        if ($this->avatar) {
            $path = $this->avatar->store('avatars', 'public');
            $profile->update(['avatar' => $path]);
        }

        return $this->redirect(route('feed.index'), navigate: true);
    }

    public function skip()
    {
        $profile = auth()->user()->getOrCreateProfile();
        $profile->update(['onboarding_completed' => true]);

        return $this->redirect(route('feed.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.onboarding.welcome')->layout('layouts.app');
    }
}
