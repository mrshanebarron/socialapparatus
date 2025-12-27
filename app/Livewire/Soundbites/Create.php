<?php

namespace App\Livewire\Soundbites;

use App\Models\Soundbite;
use App\Models\SoundbiteCategory;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $title = '';
    public $description = '';
    public $audio;
    public $cover;
    public $category_id = null;
    public $privacy = 'public';
    public $allow_comments = true;
    public $allow_duets = true;

    public $categories;

    protected $rules = [
        'audio' => 'required|file|mimes:mp3,wav,m4a,ogg|max:51200',
        'cover' => 'nullable|image|max:2048',
        'title' => 'nullable|max:255',
        'category_id' => 'nullable|exists:soundbite_categories,id',
        'privacy' => 'required|in:public,friends,private',
    ];

    public function mount()
    {
        $this->categories = SoundbiteCategory::where('is_active', true)->orderBy('sort_order')->get();
    }

    public function create()
    {
        $this->validate();

        $audioPath = $this->audio->store('soundbites', 'public');
        $coverPath = $this->cover ? $this->cover->store('soundbite-covers', 'public') : null;

        // Get duration (simplified - would need ffprobe in production)
        $duration = 60; // Default, would be calculated from audio file

        $soundbite = Soundbite::create([
            'user_id' => auth()->id(),
            'category_id' => $this->category_id,
            'title' => $this->title,
            'description' => $this->description,
            'audio_path' => $audioPath,
            'cover_image' => $coverPath,
            'duration_seconds' => $duration,
            'privacy' => $this->privacy,
            'allow_comments' => $this->allow_comments,
            'allow_duets' => $this->allow_duets,
        ]);

        session()->flash('success', 'Soundbite created successfully!');
        return redirect()->route('soundbites.show', $soundbite);
    }

    public function render()
    {
        return view('livewire.soundbites.create');
    }
}
