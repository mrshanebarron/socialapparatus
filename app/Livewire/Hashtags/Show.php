<?php

namespace App\Livewire\Hashtags;

use App\Models\Hashtag;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public Hashtag $hashtag;

    public function mount(string $tag)
    {
        $this->hashtag = Hashtag::where('tag', strtolower($tag))->firstOrFail();
    }

    public function render()
    {
        $posts = Post::whereHas('hashtags', function ($q) {
            $q->where('hashtag_id', $this->hashtag->id);
        })
        ->with(['user.profile', 'poll.options'])
        ->published()
        ->latest()
        ->paginate(20);

        $trendingHashtags = Hashtag::trending(10)->get();

        return view('livewire.hashtags.show', [
            'posts' => $posts,
            'trendingHashtags' => $trendingHashtags,
        ]);
    }
}
