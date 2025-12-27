<?php

namespace App\Livewire\Stories;

use App\Models\StoryHighlight;
use App\Models\Story;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class Highlights extends Component
{
    use WithFileUploads;

    public User $user;
    public bool $showCreateModal = false;
    public bool $showViewerModal = false;
    public bool $showEditModal = false;

    public ?StoryHighlight $viewingHighlight = null;
    public ?StoryHighlight $editingHighlight = null;
    public int $currentItemIndex = 0;

    public string $highlightTitle = '';
    public $coverImage = null;
    public array $selectedStoryIds = [];

    protected $listeners = ['refreshHighlights' => '$refresh'];

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function openCreateModal()
    {
        if ($this->user->id !== auth()->id()) return;
        $this->showCreateModal = true;
        $this->resetFields();
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
    }

    protected function resetFields()
    {
        $this->highlightTitle = '';
        $this->coverImage = null;
        $this->selectedStoryIds = [];
    }

    public function toggleStorySelection($storyId)
    {
        if (in_array($storyId, $this->selectedStoryIds)) {
            $this->selectedStoryIds = array_diff($this->selectedStoryIds, [$storyId]);
        } else {
            $this->selectedStoryIds[] = $storyId;
        }
        $this->selectedStoryIds = array_values($this->selectedStoryIds);
    }

    public function createHighlight()
    {
        if ($this->user->id !== auth()->id()) return;

        $this->validate([
            'highlightTitle' => 'required|min:1|max:50',
            'selectedStoryIds' => 'required|array|min:1',
        ]);

        $coverPath = null;
        if ($this->coverImage) {
            $coverPath = $this->coverImage->store('highlight-covers', 'public');
        }

        $highlight = StoryHighlight::create([
            'user_id' => auth()->id(),
            'title' => $this->highlightTitle,
            'cover_image' => $coverPath,
        ]);

        foreach ($this->selectedStoryIds as $index => $storyId) {
            $highlight->items()->create([
                'story_id' => $storyId,
                'position' => $index,
            ]);
        }

        $this->closeCreateModal();
    }

    public function viewHighlight($highlightId)
    {
        $this->viewingHighlight = StoryHighlight::with(['items.story'])->findOrFail($highlightId);
        $this->currentItemIndex = 0;
        $this->showViewerModal = true;
    }

    public function closeViewer()
    {
        $this->showViewerModal = false;
        $this->viewingHighlight = null;
    }

    public function nextItem()
    {
        if ($this->viewingHighlight && $this->currentItemIndex < $this->viewingHighlight->items->count() - 1) {
            $this->currentItemIndex++;
        }
    }

    public function prevItem()
    {
        $this->currentItemIndex = max(0, $this->currentItemIndex - 1);
    }

    public function openEditModal($highlightId)
    {
        if ($this->user->id !== auth()->id()) return;

        $this->editingHighlight = StoryHighlight::with('items')->findOrFail($highlightId);
        $this->highlightTitle = $this->editingHighlight->title;
        $this->selectedStoryIds = $this->editingHighlight->items->pluck('story_id')->toArray();
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingHighlight = null;
    }

    public function updateHighlight()
    {
        if (!$this->editingHighlight || $this->user->id !== auth()->id()) return;

        $this->validate([
            'highlightTitle' => 'required|min:1|max:50',
        ]);

        if ($this->coverImage) {
            $coverPath = $this->coverImage->store('highlight-covers', 'public');
            $this->editingHighlight->update(['cover_image' => $coverPath]);
        }

        $this->editingHighlight->update(['title' => $this->highlightTitle]);

        // Update items
        $this->editingHighlight->items()->delete();
        foreach ($this->selectedStoryIds as $index => $storyId) {
            $this->editingHighlight->items()->create([
                'story_id' => $storyId,
                'position' => $index,
            ]);
        }

        $this->closeEditModal();
    }

    public function deleteHighlight($highlightId)
    {
        if ($this->user->id !== auth()->id()) return;

        $highlight = StoryHighlight::findOrFail($highlightId);
        $highlight->items()->delete();
        $highlight->delete();
    }

    public function render()
    {
        $highlights = StoryHighlight::where('user_id', $this->user->id)
            ->with(['items.story'])
            ->orderBy('position')
            ->get();

        $archivedStories = $this->user->id === auth()->id()
            ? Story::where('user_id', auth()->id())
                ->where('is_archived', true)
                ->orderByDesc('created_at')
                ->get()
            : collect();

        return view('livewire.stories.highlights', [
            'highlights' => $highlights,
            'archivedStories' => $archivedStories,
            'isOwner' => $this->user->id === auth()->id(),
        ]);
    }
}
