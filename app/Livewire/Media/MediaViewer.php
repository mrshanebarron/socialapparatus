<?php

namespace App\Livewire\Media;

use App\Models\Media;
use Livewire\Component;

class MediaViewer extends Component
{
    public ?Media $media = null;
    public bool $showViewer = false;

    protected $listeners = ['openMediaViewer' => 'openViewer'];

    public function openViewer($mediaId)
    {
        $this->media = Media::with('user.profile')->findOrFail($mediaId);

        if (!$this->media->isVisibleTo(auth()->user())) {
            return;
        }

        $this->showViewer = true;
    }

    public function closeViewer()
    {
        $this->showViewer = false;
        $this->media = null;
    }

    public function toggleLike()
    {
        if (!$this->media || !auth()->check()) {
            return;
        }

        $this->media->toggleLike(auth()->user());
        $this->media->refresh();
    }

    public function deleteMedia()
    {
        if (!$this->media || $this->media->user_id !== auth()->id()) {
            return;
        }

        if ($this->media->album) {
            $this->media->album->decrement('media_count');
        }

        $this->media->delete();
        $this->closeViewer();
        $this->dispatch('mediaDeleted');
    }

    public function render()
    {
        return view('livewire.media.media-viewer');
    }
}
