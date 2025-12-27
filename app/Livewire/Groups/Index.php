<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filter = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function render()
    {
        $query = Group::visibleTo(auth()->user())
            ->withCount('approvedMembers')
            ->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filter === 'my') {
            $query->whereHas('members', function ($q) {
                $q->where('user_id', auth()->id())->where('status', 'approved');
            });
        } elseif ($this->filter === 'owned') {
            $query->where('owner_id', auth()->id());
        }

        return view('livewire.groups.index', [
            'groups' => $query->paginate(12),
        ])->layout('layouts.app');
    }
}
