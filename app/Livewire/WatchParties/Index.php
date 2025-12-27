<?php

namespace App\Livewire\WatchParties;

use App\Models\WatchParty;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $filter = 'upcoming';

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function render()
    {
        $query = WatchParty::with(['host', 'group']);

        if ($this->filter === 'live') {
            $query->live();
        } elseif ($this->filter === 'upcoming') {
            $query->where('status', 'scheduled')->where('scheduled_at', '>', now())->orderBy('scheduled_at');
        } elseif ($this->filter === 'mine') {
            $query->where('host_id', auth()->id());
        }

        return view('livewire.watch-parties.index', [
            'parties' => $query->paginate(12),
        ]);
    }
}
