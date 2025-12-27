<?php

namespace App\Livewire\Blog;

use App\Models\Article;
use Livewire\Component;

class Show extends Component
{
    public Article $article;
    public string $newComment = '';
    public bool $showComments = true;

    public function mount(Article $article)
    {
        if (!$article->isVisibleTo(auth()->user())) {
            abort(403);
        }

        $this->article = $article;

        // Increment views for non-owners
        if (!auth()->check() || auth()->id() !== $article->user_id) {
            $article->incrementViews();
        }
    }

    public function toggleLike()
    {
        if (!auth()->check()) return;

        $this->article->toggleLike(auth()->user());
        $this->article->refresh();
    }

    public function addComment()
    {
        if (!auth()->check() || !$this->article->comments_enabled || !trim($this->newComment)) {
            return;
        }

        $this->article->comments()->create([
            'user_id' => auth()->id(),
            'body' => trim($this->newComment),
        ]);

        $this->article->increment('comments_count');
        $this->newComment = '';
        $this->article->refresh();
    }

    public function render()
    {
        $this->article->load(['user.profile', 'comments.user.profile']);

        return view('livewire.blog.show')->layout('layouts.app');
    }
}
