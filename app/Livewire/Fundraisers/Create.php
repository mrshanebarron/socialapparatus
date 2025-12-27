<?php

namespace App\Livewire\Fundraisers;

use App\Models\Fundraiser;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $story = '';
    public float $goalAmount = 1000;
    public string $category = 'other';
    public string $beneficiaryName = '';
    public ?string $endsAt = null;
    public $coverImage = null;

    protected $rules = [
        'title' => 'required|string|max:255',
        'story' => 'required|string|min:100|max:10000',
        'goalAmount' => 'required|numeric|min:100',
        'category' => 'required|string',
        'beneficiaryName' => 'nullable|string|max:255',
        'endsAt' => 'nullable|date|after:now',
        'coverImage' => 'nullable|image|max:5120',
    ];

    public function createFundraiser()
    {
        $this->validate();

        $coverImagePath = null;
        if ($this->coverImage) {
            $coverImagePath = $this->coverImage->store('fundraisers', 'public');
        }

        $fundraiser = Fundraiser::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'story' => $this->story,
            'cover_image' => $coverImagePath,
            'category' => $this->category,
            'goal_amount' => $this->goalAmount,
            'raised_amount' => 0,
            'currency' => 'USD',
            'beneficiary_name' => $this->beneficiaryName ?: null,
            'status' => 'active',
            'donors_count' => 0,
            'shares_count' => 0,
            'ends_at' => $this->endsAt ?: null,
        ]);

        session()->flash('success', 'Fundraiser created successfully!');
        return $this->redirect(route('fundraisers.show', $fundraiser), navigate: true);
    }

    public function render()
    {
        return view('livewire.fundraisers.create', [
            'categories' => Fundraiser::CATEGORIES,
        ]);
    }
}
