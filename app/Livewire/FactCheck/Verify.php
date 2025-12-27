<?php

namespace App\Livewire\FactCheck;

use App\Models\ContentVerification;
use App\Models\FactCheckLabel;
use App\Models\FactCheckDispute;
use Livewire\Component;

class Verify extends Component
{
    public $verifiableType;
    public $verifiableId;
    public ?ContentVerification $verification = null;

    public bool $showVerifyModal = false;
    public bool $showDisputeModal = false;

    public ?int $selectedLabelId = null;
    public string $explanation = '';
    public string $sourceUrl = '';

    public string $disputeReason = '';
    public string $disputeEvidence = '';

    public function mount($verifiableType, $verifiableId)
    {
        $this->verifiableType = $verifiableType;
        $this->verifiableId = $verifiableId;

        $this->verification = ContentVerification::where('verifiable_type', $verifiableType)
            ->where('verifiable_id', $verifiableId)
            ->with(['label', 'verifier'])
            ->first();
    }

    public function openVerifyModal()
    {
        // Only fact-checkers can verify
        if (!auth()->user()?->canFactCheck()) {
            session()->flash('error', 'You do not have permission to verify content.');
            return;
        }
        $this->showVerifyModal = true;
    }

    public function closeVerifyModal()
    {
        $this->showVerifyModal = false;
        $this->reset(['selectedLabelId', 'explanation', 'sourceUrl']);
    }

    public function submitVerification()
    {
        if (!auth()->user()?->canFactCheck()) return;

        $this->validate([
            'selectedLabelId' => 'required|exists:fact_check_labels,id',
            'explanation' => 'required|min:20|max:2000',
            'sourceUrl' => 'nullable|url|max:500',
        ]);

        $this->verification = ContentVerification::updateOrCreate(
            [
                'verifiable_type' => $this->verifiableType,
                'verifiable_id' => $this->verifiableId,
            ],
            [
                'fact_check_label_id' => $this->selectedLabelId,
                'verifier_id' => auth()->id(),
                'explanation' => $this->explanation,
                'source_url' => $this->sourceUrl,
                'verified_at' => now(),
            ]
        );

        $this->closeVerifyModal();
    }

    public function openDisputeModal()
    {
        if (!$this->verification) return;
        $this->showDisputeModal = true;
    }

    public function closeDisputeModal()
    {
        $this->showDisputeModal = false;
        $this->reset(['disputeReason', 'disputeEvidence']);
    }

    public function submitDispute()
    {
        if (!$this->verification) return;

        $this->validate([
            'disputeReason' => 'required|min:20|max:2000',
            'disputeEvidence' => 'nullable|max:2000',
        ]);

        FactCheckDispute::create([
            'content_verification_id' => $this->verification->id,
            'user_id' => auth()->id(),
            'reason' => $this->disputeReason,
            'evidence' => $this->disputeEvidence,
        ]);

        $this->verification->update(['is_disputed' => true]);

        $this->closeDisputeModal();
        session()->flash('message', 'Dispute submitted for review.');
    }

    public function render()
    {
        $labels = FactCheckLabel::orderBy('severity')->get();
        $disputes = $this->verification
            ? $this->verification->disputes()->with('user')->latest()->get()
            : collect();

        return view('livewire.fact-check.verify', [
            'labels' => $labels,
            'disputes' => $disputes,
        ]);
    }
}
