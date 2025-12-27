<?php

namespace App\Livewire\Blog;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $excerpt = '';
    public string $body = '';
    public string $visibility = 'public';
    public bool $commentsEnabled = true;
    public $featuredImage = null;

    protected $rules = [
        'title' => 'required|min:5|max:200',
        'excerpt' => 'nullable|max:500',
        'body' => 'required|min:100',
        'visibility' => 'required|in:public,friends,private',
        'featuredImage' => 'nullable|image|max:5120',
    ];

    public function saveDraft()
    {
        $this->validate();

        $featuredImagePath = null;
        if ($this->featuredImage) {
            $featuredImagePath = $this->featuredImage->store('articles', 'public');
        }

        $article = auth()->user()->articles()->create([
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'featured_image' => $featuredImagePath,
            'visibility' => $this->visibility,
            'comments_enabled' => $this->commentsEnabled,
            'status' => 'draft',
        ]);

        return $this->redirect(route('blog.edit', $article), navigate: true);
    }

    public function publish()
    {
        $this->validate();

        $featuredImagePath = null;
        if ($this->featuredImage) {
            $featuredImagePath = $this->featuredImage->store('articles', 'public');
        }

        $article = auth()->user()->articles()->create([
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'featured_image' => $featuredImagePath,
            'visibility' => $this->visibility,
            'comments_enabled' => $this->commentsEnabled,
            'status' => 'published',
            'published_at' => now(),
        ]);

        return $this->redirect(route('blog.show', $article), navigate: true);
    }

    public function render()
    {
        return view('livewire.blog.create')->layout('layouts.app');
    }
}
