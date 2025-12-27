<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public Group $group;
    public bool $canView = true;
    public bool $canViewContent = true;
    public bool $isMember = false;
    public bool $isPending = false;
    public bool $isAdmin = false;
    public bool $isModerator = false;
    public string $activeTab = 'discussion';

    protected $listeners = ['postCreated' => '$refresh'];

    public function mount(Group $group)
    {
        $this->group = $group;
        $user = auth()->user();

        $this->canView = $group->canView($user);
        $this->canViewContent = $group->canViewContent($user);

        if ($user) {
            $this->isMember = $group->isMember($user);
            $this->isPending = $group->isPendingMember($user);
            $this->isAdmin = $group->isAdmin($user);
            $this->isModerator = $group->isModerator($user);
        }
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function joinGroup()
    {
        if (!auth()->check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        $this->group->join(auth()->user());
        $this->mount($this->group);
        $this->dispatch('group-joined');
    }

    public function leaveGroup()
    {
        $this->group->leave(auth()->user());
        $this->mount($this->group);
        $this->dispatch('group-left');
    }

    public function render()
    {
        $members = $this->canViewContent
            ? $this->group->approvedMembers()->with('user.profile')->latest()->take(12)->get()
            : collect();

        $posts = $this->canViewContent
            ? Post::where('group_id', $this->group->id)
                ->where('status', 'published')
                ->with(['user.profile', 'likes', 'comments.user'])
                ->withCount(['likes', 'comments'])
                ->latest()
                ->paginate(10)
            : collect();

        $admins = $this->group->members()
            ->whereIn('role', ['admin', 'moderator'])
            ->with('user.profile')
            ->get();

        return view('livewire.groups.show', [
            'members' => $members,
            'posts' => $posts,
            'admins' => $admins,
        ])->layout('layouts.app');
    }
}
