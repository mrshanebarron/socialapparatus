<?php

namespace App\Livewire\Feed;

use App\Models\Post;
use Livewire\Component;

class Memories extends Component
{
    public function render()
    {
        $memories = Post::getMemories(auth()->user());

        return view('livewire.feed.memories', [
            'memories' => $memories,
            'today' => now()->format('F j'),
        ]);
    }
}
