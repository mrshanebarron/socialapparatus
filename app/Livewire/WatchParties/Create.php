<?php

namespace App\Livewire\WatchParties;

use App\Models\WatchParty;
use Livewire\Component;

class Create extends Component
{
    public $title = '';
    public $description = '';
    public $video_url = '';
    public $privacy = 'friends';
    public $scheduled_at = '';
    public $max_participants = null;

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'video_url' => 'required|url',
        'privacy' => 'required|in:public,friends,group,invite_only',
        'scheduled_at' => 'nullable|date|after:now',
    ];

    public function create()
    {
        $this->validate();

        $party = WatchParty::create([
            'host_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'video_url' => $this->video_url,
            'video_provider' => $this->detectProvider($this->video_url),
            'privacy' => $this->privacy,
            'status' => $this->scheduled_at ? 'scheduled' : 'live',
            'scheduled_at' => $this->scheduled_at ?: null,
            'started_at' => $this->scheduled_at ? null : now(),
            'max_participants' => $this->max_participants,
        ]);

        return redirect()->route('watch-parties.show', $party);
    }

    protected function detectProvider($url): ?string
    {
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) return 'youtube';
        if (str_contains($url, 'vimeo.com')) return 'vimeo';
        if (str_contains($url, 'twitch.tv')) return 'twitch';
        return null;
    }

    public function render()
    {
        return view('livewire.watch-parties.create');
    }
}
