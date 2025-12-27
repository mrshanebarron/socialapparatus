<?php

namespace App\Livewire\Security;

use App\Models\LoginSession;
use Livewire\Component;

class Sessions extends Component
{
    public bool $showTerminateModal = false;
    public ?int $sessionToTerminate = null;

    public function terminateSession($sessionId)
    {
        $session = auth()->user()->loginSessions()->find($sessionId);
        if ($session && !$session->is_current) {
            $session->update(['logged_out_at' => now()]);
        }
        $this->showTerminateModal = false;
        $this->sessionToTerminate = null;
    }

    public function terminateAllOthers()
    {
        auth()->user()->loginSessions()
            ->where('is_current', false)
            ->whereNull('logged_out_at')
            ->update(['logged_out_at' => now()]);
    }

    public function render()
    {
        $sessions = auth()->user()->loginSessions()
            ->whereNull('logged_out_at')
            ->orderByDesc('last_active_at')
            ->get();

        return view('livewire.security.sessions', [
            'sessions' => $sessions,
        ])->layout('layouts.app');
    }
}
