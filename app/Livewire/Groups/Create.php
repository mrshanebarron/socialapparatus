<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $description = '';
    public string $privacy = 'public';
    public string $join_approval = 'auto';
    public bool $allow_member_posts = true;
    public bool $allow_member_invites = true;
    public $avatar;

    protected $rules = [
        'name' => 'required|min:3|max:100',
        'description' => 'nullable|max:1000',
        'privacy' => 'required|in:public,private,secret',
        'join_approval' => 'required|in:auto,admin',
        'allow_member_posts' => 'boolean',
        'allow_member_invites' => 'boolean',
        'avatar' => 'nullable|image|max:2048',
    ];

    public function createGroup()
    {
        $this->validate();

        $data = [
            'owner_id' => auth()->id(),
            'name' => $this->name,
            'description' => $this->description,
            'privacy' => $this->privacy,
            'join_approval' => $this->join_approval,
            'allow_member_posts' => $this->allow_member_posts,
            'allow_member_invites' => $this->allow_member_invites,
        ];

        if ($this->avatar) {
            $data['avatar'] = $this->avatar->store('groups/avatars', 'public');
        }

        $group = Group::create($data);

        session()->flash('success', 'Group created successfully!');
        $this->redirect(route('groups.show', $group), navigate: true);
    }

    public function render()
    {
        return view('livewire.groups.create')
            ->layout('layouts.app');
    }
}
