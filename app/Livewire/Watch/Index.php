<?php

namespace App\Livewire\Watch;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $category = 'for_you';

    public function setCategory(string $category)
    {
        $this->category = $category;
        $this->resetPage();
    }

    public function render()
    {
        // Get posts that have video content (media with video extensions or link previews with video)
        $query = Post::with(['user.profile'])
            ->published()
            ->where(function ($q) {
                // Posts with media that contains video files
                $q->whereNotNull('media')
                  ->where('media', '!=', '[]')
                  ->where(function ($q2) {
                      $q2->where('media', 'like', '%mp4%')
                         ->orWhere('media', 'like', '%webm%')
                         ->orWhere('media', 'like', '%mov%');
                  });
            })
            ->orWhere(function ($q) {
                // Posts with YouTube/video link previews
                $q->whereNotNull('link_url')
                  ->where(function ($q2) {
                      $q2->where('link_url', 'like', '%youtube.com%')
                         ->orWhere('link_url', 'like', '%youtu.be%')
                         ->orWhere('link_url', 'like', '%vimeo.com%')
                         ->orWhere('link_url', 'like', '%tiktok.com%');
                  });
            });

        switch ($this->category) {
            case 'following':
                if (auth()->check()) {
                    $followingIds = auth()->user()->following()->pluck('id');
                    $query->whereIn('user_id', $followingIds);
                }
                break;
            case 'live':
                // Placeholder for live videos feature
                $query->where('type', 'live');
                break;
            case 'saved':
                if (auth()->check()) {
                    $query->whereHas('savedBy', function ($q) {
                        $q->where('user_id', auth()->id());
                    });
                }
                break;
            case 'for_you':
            default:
                $query->orderByDesc('reactions_count')
                      ->orderByDesc('created_at');
        }

        $videos = $query->paginate(12);

        return view('livewire.watch.index', [
            'videos' => $videos,
        ]);
    }
}
