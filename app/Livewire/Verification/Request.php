<?php

namespace App\Livewire\Verification;

use App\Models\VerificationRequest;
use App\Models\VerificationDocument;
use Livewire\Component;
use Livewire\WithFileUploads;

class Request extends Component
{
    use WithFileUploads;

    public $type = 'identity';
    public $full_legal_name = '';
    public $known_as = '';
    public $description = '';
    public $category = '';
    public $links = [];
    public $documents = [];
    public $newLink = '';

    public $existingRequest = null;

    protected $rules = [
        'type' => 'required|in:identity,creator,business,organization,government,notable',
        'full_legal_name' => 'required|min:2|max:255',
        'description' => 'required|min:50|max:2000',
        'documents' => 'required|array|min:1',
        'documents.*' => 'file|mimes:jpg,jpeg,png,pdf|max:10240',
    ];

    public function mount()
    {
        $this->existingRequest = VerificationRequest::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'under_review'])
            ->first();

        $this->full_legal_name = auth()->user()->name;
    }

    public function addLink()
    {
        if (!empty($this->newLink) && filter_var($this->newLink, FILTER_VALIDATE_URL)) {
            $this->links[] = $this->newLink;
            $this->newLink = '';
        }
    }

    public function removeLink($index)
    {
        unset($this->links[$index]);
        $this->links = array_values($this->links);
    }

    public function submit()
    {
        $this->validate();

        $documentPaths = [];
        foreach ($this->documents as $doc) {
            $documentPaths[] = $doc->store('verification-documents', 'private');
        }

        $request = VerificationRequest::create([
            'user_id' => auth()->id(),
            'type' => $this->type,
            'full_legal_name' => $this->full_legal_name,
            'known_as' => $this->known_as ?: null,
            'description' => $this->description,
            'category' => $this->category ?: null,
            'documents' => $documentPaths,
            'links' => $this->links ?: null,
            'status' => 'pending',
        ]);

        foreach ($this->documents as $index => $doc) {
            VerificationDocument::create([
                'verification_request_id' => $request->id,
                'type' => 'document_' . ($index + 1),
                'file_path' => $documentPaths[$index],
                'original_name' => $doc->getClientOriginalName(),
                'mime_type' => $doc->getMimeType(),
                'file_size' => $doc->getSize(),
            ]);
        }

        session()->flash('success', 'Verification request submitted successfully!');
        return redirect()->route('verification.status');
    }

    public function render()
    {
        return view('livewire.verification.request');
    }
}
