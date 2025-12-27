<?php

namespace App\Livewire\Collections;

use App\Models\Collection;
use App\Models\CollectionItem;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public Collection $collection;
    public bool $showEditModal = false;
    public string $editName = '';
    public string $editDescription = '';
    public string $editPrivacy = '';

    public function mount(Collection $collection)
    {
        $this->collection = $collection;

        if ($collection->user_id !== auth()->id() && $collection->privacy === 'private') {
            abort(404);
        }
    }

    public function openEditModal()
    {
        $this->editName = $this->collection->name;
        $this->editDescription = $this->collection->description ?? '';
        $this->editPrivacy = $this->collection->privacy;
        $this->showEditModal = true;
    }

    public function updateCollection()
    {
        if ($this->collection->user_id !== auth()->id()) return;

        $this->validate([
            'editName' => 'required|string|max:100',
            'editDescription' => 'nullable|string|max:500',
            'editPrivacy' => 'required|in:public,friends,private',
        ]);

        $this->collection->update([
            'name' => $this->editName,
            'description' => $this->editDescription ?: null,
            'privacy' => $this->editPrivacy,
        ]);

        $this->showEditModal = false;
    }

    public function removeItem($itemId)
    {
        if ($this->collection->user_id !== auth()->id()) return;

        $this->collection->items()->where('id', $itemId)->delete();
        $this->collection->decrement('items_count');
    }

    public function deleteCollection()
    {
        if ($this->collection->user_id !== auth()->id()) return;
        if ($this->collection->is_default) return;

        $this->collection->delete();
        return redirect()->route('collections.index');
    }

    public function render()
    {
        $items = $this->collection->items()
            ->with(['collectable', 'user.profile'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('livewire.collections.show', [
            'items' => $items,
            'isOwner' => $this->collection->user_id === auth()->id(),
        ])->layout('layouts.app');
    }
}
