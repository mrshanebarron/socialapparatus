<?php

namespace App\Livewire\Series;

use App\Models\Series;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $filter = 'all';
    public string $type = '';
    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Series::with(['user.profile', 'items'])
            ->withCount(['items', 'followers']);

        if ($this->filter === 'following') {
            $query->whereHas('followers', fn($q) => $q->where('user_id', auth()->id()));
        } elseif ($this->filter === 'mine') {
            $query->where('user_id', auth()->id());
        } else {
            $query->where(function ($q) {
                $q->where('visibility', 'public')
                  ->orWhere('user_id', auth()->id());
            });
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        $series = $query->latest()->paginate(12);

        return view('livewire.series.index', [
            'series' => $series,
        ])->layout('layouts.app');
    }
}
