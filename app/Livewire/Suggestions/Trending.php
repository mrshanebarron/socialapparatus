<?php

namespace App\Livewire\Suggestions;

use App\Models\Trend;
use Livewire\Component;

class Trending extends Component
{
    public function render()
    {
        $trends = Trend::orderByDesc('daily_count')
            ->take(10)
            ->get();

        return view('livewire.suggestions.trending', [
            'trends' => $trends,
        ]);
    }
}
