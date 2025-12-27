<?php

namespace App\Livewire\Feed;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $filter = 'feed';

    protected $listeners = ['postCreated' => '$refresh', 'postDeleted' => '$refresh'];

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        if ($this->filter === 'feed') {
            $posts = Post::feed($user)->with(['user.profile', 'topLevelComments.user.profile'])->paginate(10);
        } elseif ($this->filter === 'discover') {
            // Discover shows all public posts from everyone
            $posts = Post::where('visibility', 'public')
                ->whereNull('group_id')
                ->where('status', 'published')
                ->with(['user.profile', 'topLevelComments.user.profile'])
                ->latest()
                ->paginate(10);
        } elseif ($this->filter === 'my-posts') {
            // My posts shows all of the current user's posts (including drafts for their own view)
            $posts = Post::where('user_id', $user->id)
                ->with(['user.profile', 'topLevelComments.user.profile'])
                ->latest()
                ->paginate(10);
        } else {
            // Default fallback to feed
            $posts = Post::feed($user)->with(['user.profile', 'topLevelComments.user.profile'])->paginate(10);
        }

        return view('livewire.feed.index', [
            'posts' => $posts,
        ])->layout('layouts.app');
    }
}
