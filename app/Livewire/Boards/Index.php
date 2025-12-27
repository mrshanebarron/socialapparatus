<?php

namespace App\Livewire\Boards;

use App\Models\Board;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $filter = 'all';
    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Board::with(['user.profile', 'collaborators'])
            ->withCount('items');

        if ($this->filter === 'owned') {
            $query->where('user_id', auth()->id());
        } elseif ($this->filter === 'shared') {
            $query->whereHas('collaborators', fn($q) => $q->where('user_id', auth()->id()));
        } else {
            $query->where(function ($q) {
                $q->where('user_id', auth()->id())
                  ->orWhere('visibility', 'public')
                  ->orWhereHas('collaborators', fn($q2) => $q2->where('user_id', auth()->id()));
            });
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        $boards = $query->latest()->paginate(12);

        return view('livewire.boards.index', [
            'boards' => $boards,
        ])->layout('layouts.app');
    }
}
