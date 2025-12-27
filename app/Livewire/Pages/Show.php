<?php

namespace App\Livewire\Pages;

use App\Models\Connection;
use App\Models\Page;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public Page $page;

    public function mount(Page $page)
    {
        $this->page = $page;
    }

    public function toggleFollow()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $existing = Connection::where('user_id', auth()->id())
            ->where('connectable_type', Page::class)
            ->where('connectable_id', $this->page->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->page->decrement('followers_count');
        } else {
            Connection::create([
                'user_id' => auth()->id(),
                'connectable_type' => Page::class,
                'connectable_id' => $this->page->id,
                'type' => 'follow',
                'status' => 'accepted',
            ]);
            $this->page->increment('followers_count');
        }

        $this->page->refresh();
    }

    public function render()
    {
        $isFollowing = false;
        if (auth()->check()) {
            $isFollowing = $this->page->isFollowedBy(auth()->user());
        }

        $posts = Post::where('page_id', $this->page->id)
            ->published()
            ->with(['user.profile', 'poll.options'])
            ->latest()
            ->paginate(10);

        return view('livewire.pages.show', [
            'isFollowing' => $isFollowing,
            'posts' => $posts,
        ]);
    }
}
