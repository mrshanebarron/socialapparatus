<?php

namespace App\Livewire\Search;

use App\Models\User;
use App\Models\Group;
use App\Models\Post;
use App\Models\Article;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $query = '';
    public bool $showResults = false;

    public function updatedQuery()
    {
        $this->showResults = strlen($this->query) >= 2;
    }

    public function clearSearch()
    {
        $this->query = '';
        $this->showResults = false;
    }

    public function render()
    {
        $results = [
            'users' => [],
            'groups' => [],
            'posts' => [],
            'articles' => [],
        ];

        if (strlen($this->query) >= 2) {
            $results['users'] = User::where('name', 'like', "%{$this->query}%")
                ->with('profile')
                ->limit(5)
                ->get();

            $results['groups'] = Group::where('name', 'like', "%{$this->query}%")
                ->where('privacy', '!=', 'secret')
                ->limit(5)
                ->get();

            $results['posts'] = Post::where('body', 'like', "%{$this->query}%")
                ->visibleTo(auth()->user())
                ->with('user.profile')
                ->limit(5)
                ->get();

            $results['articles'] = Article::where('title', 'like', "%{$this->query}%")
                ->orWhere('body', 'like', "%{$this->query}%")
                ->published()
                ->with('user.profile')
                ->limit(5)
                ->get();
        }

        return view('livewire.search.global-search', [
            'results' => $results,
        ]);
    }
}
