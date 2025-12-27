<?php

namespace App\Livewire\Activity;

use Livewire\Component;
use Livewire\WithPagination;

class Log extends Component
{
    use WithPagination;

    public string $category = '';
    public string $dateFrom = '';
    public string $dateTo = '';

    public function filter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['category', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function render()
    {
        $query = auth()->user()->activityLogs()
            ->with('subject')
            ->orderByDesc('created_at');

        if ($this->category) {
            $query->where('category', $this->category);
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $logs = $query->paginate(30);

        $categories = auth()->user()->activityLogs()
            ->distinct()
            ->pluck('category');

        return view('livewire.activity.log', [
            'logs' => $logs,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}
