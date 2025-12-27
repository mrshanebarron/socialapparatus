<?php

namespace App\Livewire\Feed;

use App\Models\SavedPost;
use Livewire\Component;
use Livewire\WithPagination;

class SavedPosts extends Component
{
    use WithPagination;

    public function unsavePost($savedPostId)
    {
        $savedPost = SavedPost::where('id', $savedPostId)
            ->where('user_id', auth()->id())
            ->first();

        if ($savedPost) {
            $savedPost->delete();
        }
    }

    public function render()
    {
        $savedPosts = SavedPost::where('user_id', auth()->id())
            ->with(['post.user.profile', 'post.sharedPost.user.profile', 'post.poll.options'])
            ->latest()
            ->paginate(20);

        return view('livewire.feed.saved-posts', [
            'savedPosts' => $savedPosts,
        ]);
    }
}
