<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $description = '';
    public string $location = '';
    public bool $isOnline = false;
    public string $onlineLink = '';
    public string $startsAt = '';
    public string $endsAt = '';
    public string $privacy = 'public';
    public $coverImage = null;

    protected $rules = [
        'title' => 'required|min:3|max:200',
        'description' => 'nullable|max:2000',
        'location' => 'required_if:isOnline,false|max:255',
        'onlineLink' => 'required_if:isOnline,true|nullable|url',
        'startsAt' => 'required|date|after:now',
        'endsAt' => 'nullable|date|after:startsAt',
        'privacy' => 'required|in:public,friends,private',
        'coverImage' => 'nullable|image|max:5120',
    ];

    public function createEvent()
    {
        $this->validate();

        $coverImagePath = null;
        if ($this->coverImage) {
            $coverImagePath = $this->coverImage->store('events', 'public');
        }

        $event = auth()->user()->events()->create([
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->isOnline ? null : $this->location,
            'is_online' => $this->isOnline,
            'online_link' => $this->isOnline ? $this->onlineLink : null,
            'starts_at' => $this->startsAt,
            'ends_at' => $this->endsAt ?: null,
            'privacy' => $this->privacy,
            'cover_image' => $coverImagePath,
        ]);

        return $this->redirect(route('events.show', $event), navigate: true);
    }

    public function render()
    {
        return view('livewire.events.create')->layout('layouts.app');
    }
}
