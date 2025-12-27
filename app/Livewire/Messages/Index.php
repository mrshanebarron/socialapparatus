<?php

namespace App\Livewire\Messages;

use App\Models\Conversation;
use Livewire\Component;

class Index extends Component
{
    public ?Conversation $activeConversation = null;
    public string $search = '';

    protected $listeners = ['conversationSelected', 'refreshConversations' => '$refresh'];

    public function mount(?Conversation $conversation = null)
    {
        if ($conversation && $conversation->exists && $conversation->isParticipant(auth()->user())) {
            $this->activeConversation = $conversation;
        }
    }

    public function conversationSelected($conversationId)
    {
        $conversation = Conversation::find($conversationId);
        if ($conversation && $conversation->isParticipant(auth()->user())) {
            $this->activeConversation = $conversation;
        }
    }

    public function render()
    {
        $query = auth()->user()->conversations();

        if ($this->search) {
            // Search by participant name or conversation name
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('participants.user', function ($p) {
                      $p->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        return view('livewire.messages.index', [
            'conversations' => $query->with(['latestMessage.user', 'participants.user.profile'])->get(),
        ])->layout('layouts.app');
    }
}
