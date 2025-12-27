<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class Dashboard extends Component
{
    protected $listeners = ['postCreated' => '$refresh', 'postDeleted' => '$refresh'];

    public function render()
    {
        $posts = Post::feed(auth()->user())
            ->with(['user.profile', 'topLevelComments.user.profile'])
            ->take(10)
            ->get();

        return view('livewire.dashboard', [
            'posts' => $posts,
        ])->layout('components.layouts.spa');
    }
}
