<?php

namespace App\Livewire\Soundbites;

use App\Models\Soundbite;
use App\Models\SoundbiteCategory;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $filter = 'featured';
    public $category = null;
    public $categories;

    public function mount()
    {
        $this->categories = SoundbiteCategory::where('is_active', true)->orderBy('sort_order')->get();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function setCategory($categoryId)
    {
        $this->category = $categoryId;
        $this->resetPage();
    }

    public function toggleLike($soundbiteId)
    {
        $soundbite = Soundbite::findOrFail($soundbiteId);
        $exists = $soundbite->likes()->where('user_id', auth()->id())->exists();

        if ($exists) {
            $soundbite->likes()->where('user_id', auth()->id())->delete();
            $soundbite->decrement('likes_count');
        } else {
            $soundbite->likes()->create(['user_id' => auth()->id()]);
            $soundbite->increment('likes_count');
        }
    }

    public function render()
    {
        $query = Soundbite::with(['user', 'category'])->public();

        if ($this->filter === 'featured') {
            $query->where('is_featured', true);
        } elseif ($this->filter === 'recent') {
            $query->latest();
        } elseif ($this->filter === 'popular') {
            $query->orderByDesc('plays_count');
        }

        if ($this->category) {
            $query->where('category_id', $this->category);
        }

        return view('livewire.soundbites.index', [
            'soundbites' => $query->paginate(12),
        ]);
    }
}
