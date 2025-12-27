<?php

namespace App\Livewire\Notifications;

use App\Models\Notification;
use Livewire\Component;

class NotificationDropdown extends Component
{
    public bool $showDropdown = false;

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
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
        $notifications = auth()->user()->notifications()
            ->with('fromUser.profile')
            ->latest()
            ->limit(10)
            ->get();

        $unreadCount = auth()->user()->notifications()->unread()->count();

        return view('livewire.notifications.notification-dropdown', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}
