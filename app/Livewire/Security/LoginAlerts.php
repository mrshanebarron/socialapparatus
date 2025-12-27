<?php

namespace App\Livewire\Security;

use Livewire\Component;
use Livewire\WithPagination;

class LoginAlerts extends Component
{
    use WithPagination;

    public function markAsRead($alertId)
    {
        auth()->user()->loginAlerts()->where('id', $alertId)->update(['is_read' => true]);
    }

    public function markAllAsRead()
    {
        auth()->user()->loginAlerts()->where('is_read', false)->update(['is_read' => true]);
    }

    public function render()
    {
        $alerts = auth()->user()->loginAlerts()
            ->with('loginSession')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('livewire.security.login-alerts', [
            'alerts' => $alerts,
        ]);
    }
}
