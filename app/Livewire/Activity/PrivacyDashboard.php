<?php

namespace App\Livewire\Activity;

use App\Models\DataExport;
use Livewire\Component;

class PrivacyDashboard extends Component
{
    public bool $showDeleteModal = false;
    public string $deleteReason = '';
    public string $deleteFeedback = '';
    public string $deleteConfirm = '';

    public function requestDataExport()
    {
        $pending = auth()->user()->dataExports()
            ->whereIn('status', ['pending', 'processing'])
            ->exists();

        if ($pending) {
            session()->flash('error', 'You already have a pending data export request.');
            return;
        }

        auth()->user()->dataExports()->create([
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        session()->flash('message', 'Your data export request has been submitted. You will be notified when it is ready.');
    }

    public function scheduleAccountDeletion()
    {
        if ($this->deleteConfirm !== 'DELETE') {
            session()->flash('error', 'Please type DELETE to confirm.');
            return;
        }

        $pending = auth()->user()->accountDeletions()
            ->whereNull('cancelled_at')
            ->exists();

        if ($pending) {
            session()->flash('error', 'You already have a pending account deletion request.');
            return;
        }

        auth()->user()->accountDeletions()->create([
            'reason' => $this->deleteReason ?: null,
            'feedback' => $this->deleteFeedback ?: null,
            'scheduled_for' => now()->addDays(30),
        ]);

        $this->showDeleteModal = false;
        session()->flash('message', 'Your account is scheduled for deletion in 30 days. You can cancel this at any time.');
    }

    public function cancelAccountDeletion()
    {
        auth()->user()->accountDeletions()
            ->whereNull('cancelled_at')
            ->update(['cancelled_at' => now()]);

        session()->flash('message', 'Account deletion has been cancelled.');
    }

    public function render()
    {
        $dataExports = auth()->user()->dataExports()
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $pendingDeletion = auth()->user()->accountDeletions()
            ->whereNull('cancelled_at')
            ->first();

        return view('livewire.activity.privacy-dashboard', [
            'dataExports' => $dataExports,
            'pendingDeletion' => $pendingDeletion,
        ])->layout('layouts.app');
    }
}
