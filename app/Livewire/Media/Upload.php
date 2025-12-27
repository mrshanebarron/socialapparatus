<?php

namespace App\Livewire\Media;

use App\Models\Album;
use App\Models\Media;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Upload extends Component
{
    use WithFileUploads;

    public $files = [];
    public ?int $albumId = null;
    public string $privacy = 'public';
    public bool $showModal = false;
    public array $uploadedFiles = [];

    protected $listeners = ['openUploadModal' => 'openModal'];

    protected $rules = [
        'files.*' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm|max:51200', // 50MB max
        'privacy' => 'required|in:public,friends,private',
        'albumId' => 'nullable|exists:albums,id',
    ];

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['files', 'uploadedFiles']);
    }

    public function updatedFiles()
    {
        $this->validate([
            'files.*' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm|max:51200',
        ]);

        foreach ($this->files as $file) {
            $this->uploadedFiles[] = [
                'file' => $file,
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'preview' => $file->isPreviewable() ? $file->temporaryUrl() : null,
            ];
        }
        $this->files = [];
    }

    public function removeFile($index)
    {
        unset($this->uploadedFiles[$index]);
        $this->uploadedFiles = array_values($this->uploadedFiles);
    }

    public function upload()
    {
        if (empty($this->uploadedFiles)) {
            return;
        }

        $user = auth()->user();

        foreach ($this->uploadedFiles as $uploadedFile) {
            $file = $uploadedFile['file'];
            $extension = $file->getClientOriginalExtension();
            $filename = Str::random(40) . '.' . $extension;
            $path = $file->storeAs('media/' . $user->id, $filename, 'public');

            $mimeType = $file->getMimeType();
            $type = str_starts_with($mimeType, 'video/') ? 'video' : 'image';

            $mediaData = [
                'user_id' => $user->id,
                'album_id' => $this->albumId,
                'filename' => $filename,
                'original_filename' => $file->getClientOriginalName(),
                'path' => $path,
                'disk' => 'public',
                'mime_type' => $mimeType,
                'size' => $file->getSize(),
                'type' => $type,
                'privacy' => $this->privacy,
            ];

            // Get image dimensions
            if ($type === 'image') {
                $dimensions = getimagesize($file->getRealPath());
                if ($dimensions) {
                    $mediaData['width'] = $dimensions[0];
                    $mediaData['height'] = $dimensions[1];
                }
            }

            Media::create($mediaData);

            // Update album media count
            if ($this->albumId) {
                Album::find($this->albumId)->increment('media_count');
            }
        }

        $this->closeModal();
        $this->dispatch('mediaUploaded');
    }

    public function render()
    {
        $albums = auth()->user()->albums()->orderBy('name')->get();

        return view('livewire.media.upload', [
            'albums' => $albums,
        ]);
    }
}
