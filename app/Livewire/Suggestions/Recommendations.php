<?php

namespace App\Livewire\Suggestions;

use Livewire\Component;

class Recommendations extends Component
{
    public string $type = 'people_you_may_know';

    public function dismiss($recommendationId)
    {
        auth()->user()->recommendations()
            ->where('id', $recommendationId)
            ->update(['is_dismissed' => true]);
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function render()
    {
        $recommendations = auth()->user()->recommendations()
            ->where('type', $this->type)
            ->where('is_dismissed', false)
            ->with('recommendable')
            ->orderByDesc('score')
            ->take(10)
            ->get();

        return view('livewire.suggestions.recommendations', [
            'recommendations' => $recommendations,
        ]);
    }
}
