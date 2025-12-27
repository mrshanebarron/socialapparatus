<?php

namespace App\Livewire\Pages;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $category = '';
    public string $description = '';
    public string $website = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public $avatar;
    public $coverImage;

    protected $rules = [
        'name' => 'required|min:2|max:100',
        'category' => 'required',
        'description' => 'nullable|max:1000',
        'website' => 'nullable|url|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|max:50',
        'address' => 'nullable|max:255',
        'avatar' => 'nullable|image|max:2048',
        'coverImage' => 'nullable|image|max:5120',
    ];

    public function createPage()
    {
        $this->validate([
            'name' => 'required|min:2|max:100',
            'category' => 'required|in:' . implode(',', Page::CATEGORIES),
            'description' => 'nullable|max:1000',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:50',
            'address' => 'nullable|max:255',
            'avatar' => 'nullable|image|max:2048',
            'coverImage' => 'nullable|image|max:5120',
        ]);

        $avatarPath = null;
        $coverPath = null;

        if ($this->avatar) {
            $avatarPath = $this->avatar->store('pages/avatars', 'public');
        }

        if ($this->coverImage) {
            $coverPath = $this->coverImage->store('pages/covers', 'public');
        }

        $page = Page::create([
            'owner_id' => auth()->id(),
            'name' => $this->name,
            'category' => $this->category,
            'description' => $this->description,
            'website' => $this->website ?: null,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'avatar' => $avatarPath,
            'cover_image' => $coverPath,
        ]);

        return redirect()->route('pages.show', $page);
    }

    public function render()
    {
        return view('livewire.pages.create', [
            'categories' => Page::CATEGORIES,
        ]);
    }
}
