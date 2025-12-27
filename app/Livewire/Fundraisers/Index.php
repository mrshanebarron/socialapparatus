<?php

namespace App\Livewire\Fundraisers;

use App\Models\Fundraiser;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $category = '';
    public string $sortBy = 'trending';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'sortBy' => ['except' => 'trending'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Fundraiser::with(['user.profile'])->active();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('story', 'like', "%{$this->search}%");
            });
        }

        if ($this->category) {
            $query->where('category', $this->category);
        }

        switch ($this->sortBy) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'most_raised':
                $query->orderBy('raised_amount', 'desc');
                break;
            case 'ending_soon':
                $query->whereNotNull('ends_at')
                      ->where('ends_at', '>', now())
                      ->orderBy('ends_at', 'asc');
                break;
            case 'trending':
            default:
                $query->orderByRaw('(donors_count + shares_count) DESC')
                      ->orderBy('created_at', 'desc');
        }

        $fundraisers = $query->paginate(12);

        return view('livewire.fundraisers.index', [
            'fundraisers' => $fundraisers,
            'categories' => Fundraiser::CATEGORIES,
        ]);
    }
}
