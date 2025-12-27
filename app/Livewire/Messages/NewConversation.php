<?php

namespace App\Livewire\Messages;

use App\Models\Conversation;
use App\Models\User;
use Livewire\Component;

class NewConversation extends Component
{
    public bool $showModal = false;
    public string $search = '';
    public array $selectedUsers = [];
    public string $groupName = '';
    public bool $isGroup = false;

    public function openModal()
    {
        $this->showModal = true;
        $this->reset(['search', 'selectedUsers', 'groupName', 'isGroup']);
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function toggleUser($userId)
    {
        if (in_array($userId, $this->selectedUsers)) {
            $this->selectedUsers = array_diff($this->selectedUsers, [$userId]);
        } else {
            $this->selectedUsers[] = $userId;
        }

        // Auto-enable group mode if more than 1 user selected
        $this->isGroup = count($this->selectedUsers) > 1;
    }

    public function createConversation()
    {
        if (empty($this->selectedUsers)) {
            return;
        }

        if ($this->isGroup) {
            $this->validate([
                'groupName' => 'required|min:2|max:100',
            ]);

            $conversation = Conversation::createGroup(
                auth()->user(),
                $this->groupName,
                $this->selectedUsers
            );
        } else {
            $user = User::find($this->selectedUsers[0]);
            if (!$user) return;

            $conversation = auth()->user()->startConversationWith($user);
        }

        $this->closeModal();
        $this->redirect(route('messages.show', $conversation), navigate: true);
    }

    public function render()
    {
        $users = collect();

        if ($this->search && strlen($this->search) >= 2) {
            $users = User::where('id', '!=', auth()->id())
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('profile', function ($p) {
                          $p->where('display_name', 'like', '%' . $this->search . '%')
                            ->orWhere('username', 'like', '%' . $this->search . '%');
                      });
                })
                ->with('profile')
                ->limit(10)
                ->get();
        }

        return view('livewire.messages.new-conversation', [
            'users' => $users,
        ]);
    }
}
