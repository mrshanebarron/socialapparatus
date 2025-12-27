<?php

namespace App\Livewire\Series;

use App\Models\Series;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Create extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $description = '';
    public string $type = 'playlist';
    public string $visibility = 'public';
    public $coverImage = null;

    protected $rules = [
        'title' => 'required|min:2|max:200',
        'description' => 'nullable|max:2000',
        'type' => 'required|in:playlist,course,documentary,tutorial',
        'visibility' => 'required|in:public,private,unlisted',
        'coverImage' => 'nullable|image|max:5120',
    ];

    public function create()
    {
        $this->validate();

        $coverPath = null;
        if ($this->coverImage) {
            $coverPath = $this->coverImage->store('series-covers', 'public');
        }

        $series = Series::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'slug' => Str::slug($this->title) . '-' . Str::random(6),
            'description' => $this->description,
            'type' => $this->type,
            'cover_image' => $coverPath,
            'visibility' => $this->visibility,
        ]);

        return redirect()->route('series.show', $series);
    }

    public function render()
    {
        return view('livewire.series.create')->layout('layouts.app');
    }
}
