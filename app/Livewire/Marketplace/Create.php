<?php

namespace App\Livewire\Marketplace;

use App\Models\MarketplaceCategory;
use App\Models\MarketplaceListing;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $description = '';
    public float $price = 0;
    public string $condition = 'good';
    public ?int $categoryId = null;
    public string $locationName = '';
    public bool $isNegotiable = false;
    public bool $isShippingAvailable = false;
    public array $images = [];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:5000',
        'price' => 'required|numeric|min:0',
        'condition' => 'required|in:new,like_new,good,fair,poor',
        'categoryId' => 'required|exists:marketplace_categories,id',
        'locationName' => 'nullable|string|max:255',
        'images.*' => 'image|max:5120',
    ];

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    public function createListing()
    {
        $this->validate();

        $imagesPaths = [];
        foreach ($this->images as $image) {
            $imagesPaths[] = $image->store('marketplace', 'public');
        }

        MarketplaceListing::create([
            'user_id' => auth()->id(),
            'category_id' => $this->categoryId,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'condition' => $this->condition,
            'images' => $imagesPaths,
            'location_name' => $this->locationName ?: null,
            'is_negotiable' => $this->isNegotiable,
            'is_shipping_available' => $this->isShippingAvailable,
            'status' => 'active',
        ]);

        session()->flash('success', 'Listing created successfully!');
        return $this->redirect(route('marketplace.index'), navigate: true);
    }

    public function render()
    {
        $categories = MarketplaceCategory::whereNull('parent_id')
            ->with('children')
            ->orderBy('name')
            ->get();

        return view('livewire.marketplace.create', [
            'categories' => $categories,
            'conditions' => MarketplaceListing::CONDITIONS,
        ]);
    }
}
