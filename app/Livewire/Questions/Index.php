<?php

namespace App\Livewire\Questions;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $filter = 'pending';

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function render()
    {
        $questions = auth()->user()->receivedQuestions()
            ->with(['asker.profile'])
            ->when($this->filter !== 'all', function ($q) {
                $q->where('status', $this->filter);
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        $counts = [
            'pending' => auth()->user()->receivedQuestions()->where('status', 'pending')->count(),
            'answered' => auth()->user()->receivedQuestions()->where('status', 'answered')->count(),
        ];

        return view('livewire.questions.index', [
            'questions' => $questions,
            'counts' => $counts,
        ])->layout('layouts.app');
    }
}
