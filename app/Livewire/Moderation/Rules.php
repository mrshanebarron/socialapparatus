<?php

namespace App\Livewire\Moderation;

use App\Models\ModerationRule;
use Livewire\Component;

class Rules extends Component
{
    public bool $showCreateModal = false;

    public string $ruleName = '';
    public string $ruleType = 'keyword';
    public string $pattern = '';
    public string $action = 'flag';
    public string $severity = 'low';
    public bool $isActive = true;

    public function mount()
    {
        if (!auth()->user()?->isModerator()) {
            abort(403);
        }
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->reset(['ruleName', 'ruleType', 'pattern', 'action', 'severity', 'isActive']);
    }

    public function createRule()
    {
        $this->validate([
            'ruleName' => 'required|min:2|max:100',
            'ruleType' => 'required|in:keyword,regex,spam,link',
            'pattern' => 'required|max:500',
            'action' => 'required|in:flag,hide,delete,ban',
            'severity' => 'required|in:low,medium,high',
        ]);

        ModerationRule::create([
            'name' => $this->ruleName,
            'type' => $this->ruleType,
            'pattern' => $this->pattern,
            'action' => $this->action,
            'severity' => $this->severity,
            'is_active' => $this->isActive,
            'created_by' => auth()->id(),
        ]);

        $this->closeCreateModal();
    }

    public function toggleRule($ruleId)
    {
        $rule = ModerationRule::findOrFail($ruleId);
        $rule->update(['is_active' => !$rule->is_active]);
    }

    public function deleteRule($ruleId)
    {
        ModerationRule::where('id', $ruleId)->delete();
    }

    public function render()
    {
        $rules = ModerationRule::with('creator')->latest()->get();

        return view('livewire.moderation.rules', [
            'rules' => $rules,
        ])->layout('layouts.app');
    }
}
