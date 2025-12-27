<?php

namespace App\Livewire\Messages;

use App\Models\Conversation;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChatBox extends Component
{
    use WithFileUploads;

    public ?Conversation $conversation = null;
    public string $message = '';
    public bool $isTyping = false;
    public $voiceNote = null;

    protected $listeners = ['conversationSelected' => 'loadConversation'];

    public function mount(?Conversation $conversation = null)
    {
        if ($conversation) {
            $this->loadConversation($conversation->id);
        }
    }

    public function loadConversation($conversationId)
    {
        // Stop typing in previous conversation
        if ($this->conversation) {
            $this->conversation->setTyping(auth()->user(), false);
        }

        $conversation = Conversation::find($conversationId);
        if ($conversation && $conversation->isParticipant(auth()->user())) {
            $this->conversation = $conversation;
            $this->conversation->markAsReadFor(auth()->user());
        }
    }

    public function updatedMessage()
    {
        if ($this->conversation && strlen(trim($this->message)) > 0) {
            if (!$this->isTyping) {
                $this->isTyping = true;
                $this->conversation->setTyping(auth()->user(), true);
            }
        } else {
            if ($this->isTyping) {
                $this->isTyping = false;
                $this->conversation->setTyping(auth()->user(), false);
            }
        }
    }

    public function stopTyping()
    {
        if ($this->conversation && $this->isTyping) {
            $this->isTyping = false;
            $this->conversation->setTyping(auth()->user(), false);
        }
    }

    public function sendMessage()
    {
        if (!$this->conversation || !trim($this->message)) {
            return;
        }

        $this->conversation->sendMessage(auth()->user(), trim($this->message));
        $this->message = '';
        $this->isTyping = false;
        $this->dispatch('messageSent');
        $this->dispatch('refreshConversations');
    }

    public function sendVoiceNote($audioData)
    {
        if (!$this->conversation || !$audioData) {
            return;
        }

        // Decode base64 audio data
        $audioContent = base64_decode(preg_replace('#^data:audio/\w+;base64,#i', '', $audioData));

        // Generate filename
        $filename = 'voice_notes/' . auth()->id() . '_' . time() . '.webm';

        // Store the file
        \Storage::disk('public')->put($filename, $audioContent);

        // Create message with voice note
        $this->conversation->messages()->create([
            'user_id' => auth()->id(),
            'body' => '',
            'type' => 'voice',
            'voice_note' => $filename,
        ]);

        $this->conversation->update(['last_message_at' => now()]);
        $this->conversation->markAsReadFor(auth()->user());

        $this->dispatch('messageSent');
        $this->dispatch('refreshConversations');
    }

    public function render()
    {
        $messages = $this->conversation
            ? $this->conversation->messages()->with(['user.profile', 'reactions'])->oldest()->get()
            : collect();

        $typingUsers = $this->conversation
            ? $this->conversation->getTypingUsers(auth()->user())
            : [];

        // Get read receipts for the last message
        $lastMessage = $messages->last();
        $readBy = ($lastMessage && $lastMessage->user_id === auth()->id())
            ? $this->conversation->getReadByUsers($lastMessage, auth()->user())
            : [];

        return view('livewire.messages.chat-box', [
            'messages' => $messages,
            'typingUsers' => $typingUsers,
            'readBy' => $readBy,
        ]);
    }
}
