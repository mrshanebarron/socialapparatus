<?php

namespace App\Livewire\Blog;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Article $article;
    public string $title = '';
    public string $excerpt = '';
    public string $body = '';
    public string $visibility = 'public';
    public bool $commentsEnabled = true;
    public $featuredImage = null;
    public ?string $existingFeaturedImage = null;

    protected $rules = [
        'title' => 'required|min:5|max:200',
        'excerpt' => 'nullable|max:500',
        'body' => 'required|min:100',
        'visibility' => 'required|in:public,friends,private',
        'featuredImage' => 'nullable|image|max:5120',
    ];

    public function mount(Article $article)
    {
        if ($article->user_id !== auth()->id()) {
            abort(403);
        }

        $this->article = $article;
        $this->title = $article->title;
        $this->excerpt = $article->excerpt ?? '';
        $this->body = $article->body;
        $this->visibility = $article->visibility;
        $this->commentsEnabled = $article->comments_enabled;
        $this->existingFeaturedImage = $article->featured_image;
    }

    public function saveDraft()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'visibility' => $this->visibility,
            'comments_enabled' => $this->commentsEnabled,
        ];

        if ($this->featuredImage) {
            $data['featured_image'] = $this->featuredImage->store('articles', 'public');
        }

        $this->article->update($data);
        session()->flash('message', 'Draft saved successfully.');
    }

    public function publish()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'visibility' => $this->visibility,
            'comments_enabled' => $this->commentsEnabled,
            'status' => 'published',
            'published_at' => $this->article->published_at ?? now(),
        ];

        if ($this->featuredImage) {
            $data['featured_image'] = $this->featuredImage->store('articles', 'public');
        }

        $this->article->update($data);

        return $this->redirect(route('blog.show', $this->article), navigate: true);
    }

    public function unpublish()
    {
        $this->article->unpublish();
        session()->flash('message', 'Article unpublished.');
    }

    public function removeImage()
    {
        $this->article->update(['featured_image' => null]);
        $this->existingFeaturedImage = null;
    }

    public function delete()
    {
        $this->article->delete();
        return $this->redirect(route('blog.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.blog.edit')->layout('layouts.app');
    }
}
