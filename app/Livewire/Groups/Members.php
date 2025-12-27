<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use Livewire\Component;
use Livewire\WithPagination;

class Members extends Component
{
    use WithPagination;

    public Group $group;
    public string $search = '';
    public string $tab = 'members';
    public bool $isAdmin = false;
    public bool $isModerator = false;

    public function mount(Group $group)
    {
        $this->group = $group;
        $user = auth()->user();

        if ($user) {
            $this->isAdmin = $group->isAdmin($user);
            $this->isModerator = $group->isModerator($user);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function approveMember($memberId)
    {
        if (!$this->isModerator) return;

        $member = $this->group->members()->find($memberId);
        $member?->approve();
    }

    public function rejectMember($memberId)
    {
        if (!$this->isModerator) return;

        $member = $this->group->members()->find($memberId);
        $member?->reject();
    }

    public function removeMember($memberId)
    {
        if (!$this->isModerator) return;

        $member = $this->group->members()->find($memberId);
        if ($member && $member->user_id !== $this->group->owner_id) {
            if ($member->status === 'approved') {
                $this->group->decrement('members_count');
            }
            $member->delete();
        }
    }

    public function promoteMember($memberId, $role)
    {
        if (!$this->isAdmin) return;

        $member = $this->group->members()->find($memberId);
        $member?->promote($role);
    }

    public function render()
    {
        $query = $this->group->members()->with('user.profile');

        if ($this->tab === 'pending') {
            $query->where('status', 'pending');
        } else {
            $query->where('status', 'approved');
        }

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.groups.members', [
            'members' => $query->paginate(20),
            'pendingCount' => $this->group->pendingMembers()->count(),
        ])->layout('layouts.app');
    }
}
