<?php

namespace App\Livewire\Media;

use App\Models\Album;
use App\Models\Media;
use Livewire\Component;
use Livewire\WithPagination;

class AlbumShow extends Component
{
    use WithPagination;

    public Album $album;

    public function mount(Album $album)
    {
        $user = auth()->user();
        if (!$album->isVisibleTo($user)) {
            abort(403);
        }

        $this->album = $album;
    }

    public function deleteMedia($mediaId)
    {
        $media = Media::findOrFail($mediaId);

        if ($media->user_id !== auth()->id()) {
            return;
        }

        $this->album->decrement('media_count');
        $media->delete();
    }

    public function render()
    {
        $media = $this->album->media()
            ->latest()
            ->paginate(24);

        return view('livewire.media.album-show', [
            'media' => $media,
        ])->layout('layouts.app');
    }
}
