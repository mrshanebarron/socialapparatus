<?php

namespace App\Livewire\Collections;

use App\Models\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $description = '';
    public string $privacy = 'public';
    public $coverImage;

    protected $rules = [
        'name' => 'required|string|max:100',
        'description' => 'nullable|string|max:500',
        'privacy' => 'required|in:public,friends,private',
        'coverImage' => 'nullable|image|max:2048',
    ];

    public function save()
    {
        $this->validate();

        $coverPath = null;
        if ($this->coverImage) {
            $coverPath = $this->coverImage->store('collections', 'public');
        }

        $collection = auth()->user()->collections()->create([
            'name' => $this->name,
            'slug' => Str::slug($this->name) . '-' . Str::random(6),
            'description' => $this->description ?: null,
            'privacy' => $this->privacy,
            'cover_image' => $coverPath,
        ]);

        return redirect()->route('collections.show', $collection);
    }

    public function render()
    {
        return view('livewire.collections.create')->layout('layouts.app');
    }
}
