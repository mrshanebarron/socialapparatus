<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;

class Show extends Component
{
    public Event $event;

    public function mount(Event $event)
    {
        if (!$event->isVisibleTo(auth()->user())) {
            abort(403);
        }

        $this->event = $event;
    }

    public function rsvp($status)
    {
        if (!auth()->check()) return;

        $this->event->setRsvp(auth()->user(), $status);
        $this->event->refresh();
    }

    public function isVisibleTo($user)
    {
        if ($this->event->privacy === 'public') return true;
        if (!$user) return false;
        if ($this->event->user_id === $user->id) return true;
        if ($this->event->privacy === 'friends') {
            return $this->event->user->isFriendsWith($user);
        }
        return false;
    }

    public function render()
    {
        $this->event->load(['user.profile', 'goingUsers.user.profile', 'interestedUsers.user.profile']);

        $userRsvp = auth()->check() ? $this->event->getRsvpStatus(auth()->user()) : null;

        return view('livewire.events.show', [
            'userRsvp' => $userRsvp,
        ])->layout('layouts.app');
    }
}
