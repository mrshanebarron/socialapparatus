<?php

namespace App\Livewire\Media;

use App\Models\Album;
use App\Models\Media;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $filter = 'photos';

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        if ($this->filter === 'photos') {
            $items = Media::where('user_id', $user->id)
                ->images()
                ->latest()
                ->paginate(20);
            $viewType = 'media';
        } elseif ($this->filter === 'videos') {
            $items = Media::where('user_id', $user->id)
                ->videos()
                ->latest()
                ->paginate(20);
            $viewType = 'media';
        } else {
            $items = Album::where('user_id', $user->id)
                ->withCount('media')
                ->latest()
                ->paginate(20);
            $viewType = 'albums';
        }

        return view('livewire.media.index', [
            'items' => $items,
            'viewType' => $viewType,
        ])->layout('layouts.app');
    }
}
