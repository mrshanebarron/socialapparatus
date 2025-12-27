<?php

namespace App\Livewire\Media;

use App\Models\Album;
use Livewire\Component;

class CreateAlbum extends Component
{
    public string $name = '';
    public string $description = '';
    public string $privacy = 'public';
    public bool $showModal = false;

    protected $listeners = ['openCreateAlbumModal' => 'openModal'];

    protected $rules = [
        'name' => 'required|min:2|max:100',
        'description' => 'nullable|max:500',
        'privacy' => 'required|in:public,friends,private',
    ];

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'description', 'privacy']);
    }

    public function createAlbum()
    {
        $this->validate();

        $album = auth()->user()->albums()->create([
            'name' => $this->name,
            'description' => $this->description,
            'privacy' => $this->privacy,
        ]);

        $this->closeModal();
        $this->dispatch('albumCreated', albumId: $album->id);
    }

    public function render()
    {
        return view('livewire.media.create-album');
    }
}
