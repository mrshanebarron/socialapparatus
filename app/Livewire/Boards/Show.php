<?php

namespace App\Livewire\Boards;

use App\Models\Board;
use App\Models\BoardColumn;
use App\Models\BoardItem;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    public Board $board;
    public bool $showAddItemModal = false;
    public bool $showItemDetailModal = false;
    public bool $showAddColumnModal = false;
    public bool $showCollaboratorsModal = false;

    public ?int $activeColumnId = null;
    public ?BoardItem $activeItem = null;

    // New item fields
    public string $itemTitle = '';
    public string $itemDescription = '';
    public ?string $itemDueDate = null;
    public string $itemPriority = 'medium';
    public $itemImage = null;

    // New column
    public string $columnName = '';
    public string $columnColor = '#6366f1';

    // Collaborator
    public string $collaboratorSearch = '';

    protected $listeners = ['refreshBoard' => '$refresh'];

    public function mount(Board $board)
    {
        $this->board = $board;

        // Check visibility
        if (!$this->canView()) {
            abort(403);
        }
    }

    protected function canView(): bool
    {
        if ($this->board->user_id === auth()->id()) return true;
        if ($this->board->visibility === 'public') return true;
        return $this->board->collaborators()->where('user_id', auth()->id())->exists();
    }

    protected function canEdit(): bool
    {
        if ($this->board->user_id === auth()->id()) return true;
        $collab = $this->board->collaborators()->where('user_id', auth()->id())->first();
        return $collab && in_array($collab->role, ['admin', 'editor']);
    }

    public function openAddItemModal($columnId)
    {
        if (!$this->canEdit()) return;
        $this->activeColumnId = $columnId;
        $this->showAddItemModal = true;
    }

    public function closeAddItemModal()
    {
        $this->showAddItemModal = false;
        $this->resetItemFields();
    }

    protected function resetItemFields()
    {
        $this->itemTitle = '';
        $this->itemDescription = '';
        $this->itemDueDate = null;
        $this->itemPriority = 'medium';
        $this->itemImage = null;
    }

    public function createItem()
    {
        if (!$this->canEdit()) return;

        $this->validate([
            'itemTitle' => 'required|min:1|max:200',
            'itemDescription' => 'nullable|max:2000',
            'itemPriority' => 'required|in:low,medium,high,urgent',
        ]);

        $column = BoardColumn::findOrFail($this->activeColumnId);
        $position = $column->items()->max('position') ?? 0;

        $imagePath = null;
        if ($this->itemImage) {
            $imagePath = $this->itemImage->store('board-items', 'public');
        }

        BoardItem::create([
            'board_column_id' => $this->activeColumnId,
            'user_id' => auth()->id(),
            'title' => $this->itemTitle,
            'description' => $this->itemDescription,
            'image' => $imagePath,
            'due_date' => $this->itemDueDate,
            'priority' => $this->itemPriority,
            'position' => $position + 1,
        ]);

        $this->closeAddItemModal();
    }

    public function openItemDetail($itemId)
    {
        $this->activeItem = BoardItem::with(['user', 'column', 'assignees.user', 'comments.user'])->findOrFail($itemId);
        $this->showItemDetailModal = true;
    }

    public function closeItemDetail()
    {
        $this->showItemDetailModal = false;
        $this->activeItem = null;
    }

    public function moveItem($itemId, $newColumnId, $newPosition)
    {
        if (!$this->canEdit()) return;

        $item = BoardItem::findOrFail($itemId);
        $item->update([
            'board_column_id' => $newColumnId,
            'position' => $newPosition,
        ]);
    }

    public function openAddColumnModal()
    {
        if (!$this->canEdit()) return;
        $this->showAddColumnModal = true;
    }

    public function closeAddColumnModal()
    {
        $this->showAddColumnModal = false;
        $this->columnName = '';
        $this->columnColor = '#6366f1';
    }

    public function createColumn()
    {
        if (!$this->canEdit()) return;

        $this->validate([
            'columnName' => 'required|min:1|max:50',
        ]);

        $position = $this->board->columns()->max('position') ?? 0;

        $this->board->columns()->create([
            'name' => $this->columnName,
            'position' => $position + 1,
            'color' => $this->columnColor,
        ]);

        $this->closeAddColumnModal();
    }

    public function deleteColumn($columnId)
    {
        if ($this->board->user_id !== auth()->id()) return;

        $column = BoardColumn::findOrFail($columnId);
        $column->items()->delete();
        $column->delete();
    }

    public function deleteItem($itemId)
    {
        if (!$this->canEdit()) return;

        $item = BoardItem::findOrFail($itemId);
        $item->delete();

        $this->closeItemDetail();
    }

    public function toggleItemComplete($itemId)
    {
        if (!$this->canEdit()) return;

        $item = BoardItem::findOrFail($itemId);
        $item->update([
            'is_completed' => !$item->is_completed,
            'completed_at' => $item->is_completed ? null : now(),
        ]);
    }

    public function render()
    {
        $this->board->load(['columns.items.user', 'columns.items.assignees.user', 'collaborators.user']);

        return view('livewire.boards.show', [
            'canEdit' => $this->canEdit(),
            'isOwner' => $this->board->user_id === auth()->id(),
        ])->layout('layouts.app');
    }
}
