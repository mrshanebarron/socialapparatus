<?php

namespace App\Livewire\Notifications;

use App\Models\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationList extends Component
{
    use WithPagination;

    public string $filter = 'all';

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && $notification->user_id === auth()->id()) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()->unread()->update(['read_at' => now()]);
    }

    public function render()
    {
        $query = auth()->user()->notifications()->with('fromUser.profile')->latest();

        if ($this->filter === 'unread') {
            $query->unread();
        }

        return view('livewire.notifications.notification-list', [
            'notifications' => $query->paginate(20),
            'unreadCount' => auth()->user()->notifications()->unread()->count(),
        ])->layout('layouts.app');
    }
}
