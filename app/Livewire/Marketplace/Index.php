<?php

namespace App\Livewire\Marketplace;

use App\Models\MarketplaceCategory;
use App\Models\MarketplaceListing;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $categoryId = null;
    public string $condition = '';
    public string $sortBy = 'newest';
    public float $minPrice = 0;
    public float $maxPrice = 0;
    public bool $localOnly = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryId' => ['except' => null],
        'condition' => ['except' => ''],
        'sortBy' => ['except' => 'newest'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    public function setCategory(?int $id)
    {
        $this->categoryId = $id;
        $this->resetPage();
    }

    public function render()
    {
        $query = MarketplaceListing::with(['user.profile', 'category'])
            ->active();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->condition) {
            $query->where('condition', $this->condition);
        }

        if ($this->minPrice > 0) {
            $query->where('price', '>=', $this->minPrice);
        }

        if ($this->maxPrice > 0) {
            $query->where('price', '<=', $this->maxPrice);
        }

        switch ($this->sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $listings = $query->paginate(20);
        $categories = MarketplaceCategory::whereNull('parent_id')
            ->with('children')
            ->orderBy('name')
            ->get();

        return view('livewire.marketplace.index', [
            'listings' => $listings,
            'categories' => $categories,
            'conditions' => MarketplaceListing::CONDITIONS,
        ]);
    }
}
