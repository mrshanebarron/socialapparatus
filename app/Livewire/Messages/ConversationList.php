<?php

namespace App\Livewire\Messages;

use App\Models\Conversation;
use Livewire\Component;

class ConversationList extends Component
{
    public ?int $activeConversationId = null;
    public string $search = '';

    public function selectConversation($conversationId)
    {
        $this->activeConversationId = $conversationId;
        $this->dispatch('conversationSelected', conversationId: $conversationId);
    }

    public function render()
    {
        $query = auth()->user()->conversations();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('participants.user', function ($p) {
                      $p->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        return view('livewire.messages.conversation-list', [
            'conversations' => $query->with(['latestMessage.user', 'participants.user.profile'])->get(),
        ]);
    }
}
