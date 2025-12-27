<?php

namespace App\Livewire\Blog;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $filter = 'all';

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        if ($this->filter === 'my-articles') {
            $articles = Article::where('user_id', $user->id)
                ->with('user.profile')
                ->latest()
                ->paginate(10);
        } elseif ($this->filter === 'drafts') {
            $articles = Article::where('user_id', $user->id)
                ->drafts()
                ->with('user.profile')
                ->latest()
                ->paginate(10);
        } else {
            $articles = Article::visibleTo($user)
                ->with('user.profile')
                ->latest('published_at')
                ->paginate(10);
        }

        return view('livewire.blog.index', [
            'articles' => $articles,
        ])->layout('layouts.app');
    }
}
