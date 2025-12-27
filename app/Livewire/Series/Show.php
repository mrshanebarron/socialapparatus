<?php

namespace App\Livewire\Series;

use App\Models\Series;
use App\Models\SeriesItem;
use App\Models\Post;
use App\Models\Video;
use App\Models\Article;
use Livewire\Component;

class Show extends Component
{
    public Series $series;
    public bool $showAddItemModal = false;
    public string $contentSearch = '';
    public string $contentType = 'post';

    protected $listeners = ['refreshSeries' => '$refresh'];

    public function mount(Series $series)
    {
        $this->series = $series;

        // Check visibility
        if ($this->series->visibility === 'private' && $this->series->user_id !== auth()->id()) {
            abort(403);
        }
    }

    public function toggleFollow()
    {
        if (!auth()->check()) return;

        $existing = $this->series->followers()->where('user_id', auth()->id())->first();

        if ($existing) {
            $existing->delete();
        } else {
            $this->series->followers()->create([
                'user_id' => auth()->id(),
            ]);
        }

        $this->series->refresh();
    }

    public function openAddItemModal()
    {
        if ($this->series->user_id !== auth()->id()) return;
        $this->showAddItemModal = true;
    }

    public function closeAddItemModal()
    {
        $this->showAddItemModal = false;
        $this->contentSearch = '';
    }

    public function addItem($contentType, $contentId)
    {
        if ($this->series->user_id !== auth()->id()) return;

        // Check if already in series
        $exists = $this->series->items()
            ->where('content_type', $contentType)
            ->where('content_id', $contentId)
            ->exists();

        if ($exists) {
            session()->flash('error', 'This item is already in the series.');
            return;
        }

        $position = $this->series->items()->max('position') ?? 0;

        $this->series->items()->create([
            'content_type' => $contentType,
            'content_id' => $contentId,
            'position' => $position + 1,
        ]);

        $this->series->updateItemCount();
        $this->closeAddItemModal();
    }

    public function removeItem($itemId)
    {
        if ($this->series->user_id !== auth()->id()) return;

        $item = SeriesItem::findOrFail($itemId);
        $item->delete();

        $this->series->updateItemCount();
    }

    public function reorderItems($items)
    {
        if ($this->series->user_id !== auth()->id()) return;

        foreach ($items as $index => $itemId) {
            SeriesItem::where('id', $itemId)->update(['position' => $index + 1]);
        }
    }

    public function markComplete($itemId)
    {
        if (!auth()->check()) return;

        $progress = $this->series->progress()->firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        $completedItems = $progress->completed_items ?? [];
        if (!in_array($itemId, $completedItems)) {
            $completedItems[] = $itemId;
            $progress->completed_items = $completedItems;
        }

        $progress->last_item_id = $itemId;
        $progress->last_watched_at = now();
        $progress->save();
    }

    public function markIncomplete($itemId)
    {
        if (!auth()->check()) return;

        $progress = $this->series->progress()->where('user_id', auth()->id())->first();
        if (!$progress) return;

        $completedItems = $progress->completed_items ?? [];
        $completedItems = array_diff($completedItems, [$itemId]);
        $progress->completed_items = array_values($completedItems);
        $progress->save();
    }

    public function render()
    {
        $this->series->load(['user.profile', 'items.content']);

        $isFollowing = auth()->check() && $this->series->followers()->where('user_id', auth()->id())->exists();
        $isOwner = auth()->id() === $this->series->user_id;

        $userProgress = auth()->check()
            ? $this->series->progress()->where('user_id', auth()->id())->first()
            : null;

        $completedCount = $userProgress ? count($userProgress->completed_items ?? []) : 0;
        $totalItems = $this->series->items->count();
        $progressPercent = $totalItems > 0 ? round(($completedCount / $totalItems) * 100) : 0;

        // Content for adding
        $availableContent = collect();
        if ($this->showAddItemModal && $this->contentSearch) {
            if ($this->contentType === 'post') {
                $availableContent = Post::where('user_id', auth()->id())
                    ->where('content', 'like', "%{$this->contentSearch}%")
                    ->limit(10)
                    ->get();
            }
        }

        return view('livewire.series.show', [
            'isFollowing' => $isFollowing,
            'isOwner' => $isOwner,
            'userProgress' => $userProgress,
            'completedCount' => $completedCount,
            'progressPercent' => $progressPercent,
            'availableContent' => $availableContent,
        ])->layout('layouts.app');
    }
}
