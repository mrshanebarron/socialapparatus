<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $filter = 'upcoming';

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        $query = Event::visibleTo($user)->with(['user.profile']);

        if ($this->filter === 'upcoming') {
            $events = $query->upcoming()->paginate(10);
        } elseif ($this->filter === 'past') {
            $events = $query->past()->paginate(10);
        } elseif ($this->filter === 'my-events') {
            $events = Event::where('user_id', $user->id)->latest()->paginate(10);
        } else {
            // Going
            $events = Event::whereHas('rsvps', fn($q) => $q->where('user_id', $user->id)->where('status', 'going'))
                ->upcoming()
                ->paginate(10);
        }

        return view('livewire.events.index', [
            'events' => $events,
        ])->layout('layouts.app');
    }
}
