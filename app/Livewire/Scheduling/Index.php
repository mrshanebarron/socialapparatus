<?php

namespace App\Livewire\Scheduling;

use App\Models\ScheduledPost;
use App\Models\PostDraft;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $tab = 'scheduled';

    public function setTab($tab)
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function cancelScheduled($id)
    {
        $post = ScheduledPost::where('user_id', auth()->id())->findOrFail($id);
        $post->update(['status' => 'cancelled']);
        session()->flash('success', 'Scheduled post cancelled.');
    }

    public function deleteDraft($id)
    {
        PostDraft::where('user_id', auth()->id())->findOrFail($id)->delete();
        session()->flash('success', 'Draft deleted.');
    }

    public function render()
    {
        $scheduledPosts = collect();
        $drafts = collect();

        if ($this->tab === 'scheduled') {
            $scheduledPosts = ScheduledPost::where('user_id', auth()->id())
                ->whereIn('status', ['scheduled', 'failed'])
                ->orderBy('scheduled_for')
                ->paginate(10);
        } else {
            $drafts = PostDraft::where('user_id', auth()->id())
                ->orderByDesc('last_edited_at')
                ->paginate(10);
        }

        return view('livewire.scheduling.index', [
            'scheduledPosts' => $scheduledPosts,
            'drafts' => $drafts,
        ]);
    }
}
