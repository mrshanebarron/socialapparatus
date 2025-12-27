<?php

namespace App\Livewire\Moderation;

use App\Models\ModerationQueue;
use App\Models\ModerationAction;
use Livewire\Component;
use Livewire\WithPagination;

class Queue extends Component
{
    use WithPagination;

    public string $filter = 'pending';
    public string $type = '';
    public string $priority = '';

    public bool $showActionModal = false;
    public ?ModerationQueue $activeItem = null;
    public string $actionType = '';
    public string $actionNotes = '';
    public bool $sendWarning = false;
    public string $warningMessage = '';

    protected $listeners = ['refreshQueue' => '$refresh'];

    public function mount()
    {
        // Only moderators/admins can access
        if (!auth()->user()?->isModerator()) {
            abort(403);
        }
    }

    public function openActionModal($itemId)
    {
        $this->activeItem = ModerationQueue::with(['reportable', 'reporter', 'user'])->findOrFail($itemId);
        $this->showActionModal = true;
        $this->resetActionFields();
    }

    public function closeActionModal()
    {
        $this->showActionModal = false;
        $this->activeItem = null;
    }

    protected function resetActionFields()
    {
        $this->actionType = '';
        $this->actionNotes = '';
        $this->sendWarning = false;
        $this->warningMessage = '';
    }

    public function takeAction()
    {
        if (!$this->activeItem || !$this->actionType) return;

        $this->validate([
            'actionType' => 'required|in:approve,remove,warn,ban,dismiss',
            'actionNotes' => 'nullable|max:1000',
        ]);

        // Create action record
        ModerationAction::create([
            'moderator_id' => auth()->id(),
            'content_type' => $this->activeItem->content_type,
            'content_id' => $this->activeItem->content_id,
            'action' => $this->actionType,
            'reason' => $this->actionNotes,
        ]);

        // Handle the action
        switch ($this->actionType) {
            case 'approve':
                $this->activeItem->update(['status' => 'approved', 'reviewed_by' => auth()->id(), 'reviewed_at' => now()]);
                if ($this->activeItem->reportable) {
                    $this->activeItem->reportable->update(['moderation_status' => 'approved']);
                }
                break;

            case 'remove':
                $this->activeItem->update(['status' => 'removed', 'reviewed_by' => auth()->id(), 'reviewed_at' => now()]);
                if ($this->activeItem->reportable) {
                    $this->activeItem->reportable->update(['moderation_status' => 'removed', 'is_hidden' => true]);
                }
                break;

            case 'warn':
                $this->activeItem->update(['status' => 'warned', 'reviewed_by' => auth()->id(), 'reviewed_at' => now()]);
                if ($this->sendWarning && $this->activeItem->user_id) {
                    \App\Models\UserWarning::create([
                        'user_id' => $this->activeItem->user_id,
                        'moderator_id' => auth()->id(),
                        'reason' => $this->warningMessage ?: 'Content policy violation',
                        'severity' => 'minor',
                        'content_type' => $this->activeItem->content_type,
                        'content_id' => $this->activeItem->content_id,
                    ]);
                }
                break;

            case 'ban':
                $this->activeItem->update(['status' => 'banned', 'reviewed_by' => auth()->id(), 'reviewed_at' => now()]);
                if ($this->activeItem->user_id) {
                    // Ban the user
                    \App\Models\User::where('id', $this->activeItem->user_id)->update(['is_banned' => true, 'banned_at' => now()]);
                }
                break;

            case 'dismiss':
                $this->activeItem->update(['status' => 'dismissed', 'reviewed_by' => auth()->id(), 'reviewed_at' => now()]);
                break;
        }

        $this->closeActionModal();
        session()->flash('message', 'Action completed successfully.');
    }

    public function escalate($itemId)
    {
        $item = ModerationQueue::findOrFail($itemId);
        $item->update(['priority' => 'high', 'is_escalated' => true]);
    }

    public function assignToMe($itemId)
    {
        $item = ModerationQueue::findOrFail($itemId);
        $item->update(['assigned_to' => auth()->id()]);
    }

    public function render()
    {
        $query = ModerationQueue::with(['reportable', 'reporter', 'user', 'assignedModerator']);

        if ($this->filter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($this->filter === 'assigned') {
            $query->where('assigned_to', auth()->id());
        } elseif ($this->filter === 'escalated') {
            $query->where('is_escalated', true)->where('status', 'pending');
        } else {
            // All
        }

        if ($this->type) {
            $query->where('content_type', $this->type);
        }

        if ($this->priority) {
            $query->where('priority', $this->priority);
        }

        $items = $query->orderByRaw("CASE WHEN priority = 'urgent' THEN 1 WHEN priority = 'high' THEN 2 WHEN priority = 'medium' THEN 3 ELSE 4 END")
            ->latest()
            ->paginate(20);

        $stats = [
            'pending' => ModerationQueue::where('status', 'pending')->count(),
            'escalated' => ModerationQueue::where('is_escalated', true)->where('status', 'pending')->count(),
            'todayReviewed' => ModerationQueue::whereDate('reviewed_at', today())->count(),
        ];

        return view('livewire.moderation.queue', [
            'items' => $items,
            'stats' => $stats,
        ])->layout('layouts.app');
    }
}
