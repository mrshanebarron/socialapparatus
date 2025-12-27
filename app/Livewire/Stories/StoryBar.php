<?php

namespace App\Livewire\Stories;

use App\Models\Story;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class StoryBar extends Component
{
    use WithFileUploads;

    public bool $showCreateModal = false;
    public bool $showViewerModal = false;
    public string $storyType = 'text';
    public string $textContent = '';
    public string $backgroundColor = '#4F46E5';
    public $mediaFile = null;
    public ?int $viewingUserId = null;
    public int $currentStoryIndex = 0;

    protected $listeners = ['refreshStories' => '$refresh'];

    public function openCreateModal()
    {
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->reset(['storyType', 'textContent', 'backgroundColor', 'mediaFile']);
    }

    public function createTextStory()
    {
        $this->validate([
            'textContent' => 'required|min:1|max:500',
        ]);

        Story::createTextStory(auth()->user(), $this->textContent, $this->backgroundColor);
        $this->closeCreateModal();
    }

    public function createMediaStory()
    {
        $this->validate([
            'mediaFile' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov|max:20480',
        ]);

        $path = $this->mediaFile->store('stories', 'public');
        $type = str_starts_with($this->mediaFile->getMimeType(), 'video/') ? 'video' : 'image';

        Story::createMediaStory(auth()->user(), $path, $type);
        $this->closeCreateModal();
    }

    public function viewUserStories($userId)
    {
        $this->viewingUserId = $userId;
        $this->currentStoryIndex = 0;
        $this->showViewerModal = true;
    }

    public function closeViewer()
    {
        $this->showViewerModal = false;
        $this->viewingUserId = null;
    }

    public function nextStory()
    {
        $this->currentStoryIndex++;
    }

    public function prevStory()
    {
        $this->currentStoryIndex = max(0, $this->currentStoryIndex - 1);
    }

    public function render()
    {
        $user = auth()->user();

        // Get users with active stories (friends + own)
        $friendIds = $user->friends()->pluck('id')->toArray();
        $friendIds[] = $user->id;

        $usersWithStories = User::whereIn('id', $friendIds)
            ->whereHas('stories', fn($q) => $q->active())
            ->with(['stories' => fn($q) => $q->active()->latest(), 'profile'])
            ->get()
            ->sortByDesc(fn($u) => $u->id === $user->id ? 1 : 0); // Put current user first

        $viewingStories = [];
        if ($this->viewingUserId) {
            $viewingStories = Story::where('user_id', $this->viewingUserId)
                ->active()
                ->with('user.profile')
                ->oldest()
                ->get();

            // Mark as viewed
            if ($viewingStories->isNotEmpty() && isset($viewingStories[$this->currentStoryIndex])) {
                $viewingStories[$this->currentStoryIndex]->markAsViewedBy($user);
            }
        }

        return view('livewire.stories.story-bar', [
            'usersWithStories' => $usersWithStories,
            'viewingStories' => $viewingStories,
            'hasOwnStory' => $user->stories()->active()->exists(),
        ]);
    }
}
