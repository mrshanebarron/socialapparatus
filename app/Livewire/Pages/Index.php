<?php

namespace App\Livewire\Pages;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $category = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Page::query()
            ->with('owner')
            ->withCount('posts');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category) {
            $query->where('category', $this->category);
        }

        $pages = $query->orderByDesc('followers_count')->paginate(12);

        return view('livewire.pages.index', [
            'pages' => $pages,
            'categories' => Page::CATEGORIES,
        ]);
    }
}
