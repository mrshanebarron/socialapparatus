<?php

namespace App\Livewire\FactCheck;

use App\Models\ContentVerification;
use App\Models\FactCheckLabel;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $filter = 'all';
    public ?int $labelId = null;

    public function render()
    {
        $query = ContentVerification::with(['verifiable', 'verifier', 'label'])
            ->latest();

        if ($this->filter === 'disputed') {
            $query->where('is_disputed', true);
        } elseif ($this->filter === 'recent') {
            $query->where('created_at', '>=', now()->subDays(7));
        }

        if ($this->labelId) {
            $query->where('fact_check_label_id', $this->labelId);
        }

        $verifications = $query->paginate(20);
        $labels = FactCheckLabel::all();

        return view('livewire.fact-check.index', [
            'verifications' => $verifications,
            'labels' => $labels,
        ])->layout('layouts.app');
    }
}
