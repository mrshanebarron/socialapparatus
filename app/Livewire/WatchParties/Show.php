<?php

namespace App\Livewire\WatchParties;

use App\Models\WatchParty;
use App\Models\WatchPartyParticipant;
use App\Models\WatchPartyMessage;
use Livewire\Component;

class Show extends Component
{
    public WatchParty $party;
    public $message = '';
    public $messages = [];

    public function mount(WatchParty $party)
    {
        $this->party = $party;
        $this->loadMessages();
        $this->joinParty();
    }

    public function loadMessages()
    {
        $this->messages = $this->party->messages()->with('user')->latest()->take(50)->get()->reverse()->values();
    }

    public function joinParty()
    {
        WatchPartyParticipant::updateOrCreate(
            ['watch_party_id' => $this->party->id, 'user_id' => auth()->id()],
            ['status' => 'joined', 'joined_at' => now()]
        );
        $this->party->increment('participant_count');
    }

    public function sendMessage()
    {
        if (empty(trim($this->message))) return;

        WatchPartyMessage::create([
            'watch_party_id' => $this->party->id,
            'user_id' => auth()->id(),
            'message' => $this->message,
            'video_timestamp' => $this->party->current_time_seconds,
            'type' => 'message',
        ]);

        $this->message = '';
        $this->loadMessages();
    }

    public function sendReaction($reaction)
    {
        WatchPartyMessage::create([
            'watch_party_id' => $this->party->id,
            'user_id' => auth()->id(),
            'message' => $reaction,
            'video_timestamp' => $this->party->current_time_seconds,
            'type' => 'reaction',
        ]);
        $this->loadMessages();
    }

    public function syncPlayback($time, $isPlaying)
    {
        if ($this->party->host_id === auth()->id()) {
            $this->party->update([
                'current_time_seconds' => $time,
                'is_playing' => $isPlaying,
            ]);
        }
    }

    public function endParty()
    {
        if ($this->party->host_id === auth()->id()) {
            $this->party->end();
            return redirect()->route('watch-parties.index');
        }
    }

    public function render()
    {
        return view('livewire.watch-parties.show', [
            'participants' => $this->party->participants()->with('user')->where('status', 'joined')->get(),
        ]);
    }
}
