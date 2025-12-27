<?php

namespace App\Livewire\Avatars;

use App\Models\AvatarCategory;
use App\Models\AvatarPart;
use App\Models\UserAvatar;
use Livewire\Component;

class Editor extends Component
{
    public $avatar;
    public $categories;
    public $selectedCategory = null;
    public $selectedParts = [];

    public function mount()
    {
        $this->categories = AvatarCategory::with(['parts' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])->orderBy('sort_order')->get();

        $this->avatar = UserAvatar::where('user_id', auth()->id())->where('is_primary', true)->first();

        if (!$this->avatar) {
            $this->avatar = UserAvatar::create([
                'user_id' => auth()->id(),
                'name' => 'My Avatar',
                'customizations' => [],
                'is_primary' => true,
            ]);
        }

        $this->selectedParts = $this->avatar->customizations ?? [];
        $this->selectedCategory = $this->categories->first()?->id;
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
    }

    public function selectPart($partId)
    {
        $part = AvatarPart::findOrFail($partId);
        $category = $part->category;

        $this->selectedParts[$category->slug] = [
            'part_id' => $partId,
            'color' => null,
        ];

        $this->saveAvatar();
    }

    public function setPartColor($categorySlug, $color)
    {
        if (isset($this->selectedParts[$categorySlug])) {
            $this->selectedParts[$categorySlug]['color'] = $color;
            $this->saveAvatar();
        }
    }

    public function removePart($categorySlug)
    {
        unset($this->selectedParts[$categorySlug]);
        $this->saveAvatar();
    }

    protected function saveAvatar()
    {
        $this->avatar->update([
            'customizations' => $this->selectedParts,
            'rendered_image' => null,
        ]);
    }

    public function render()
    {
        $currentCategory = $this->categories->firstWhere('id', $this->selectedCategory);

        return view('livewire.avatars.editor', [
            'currentCategory' => $currentCategory,
            'parts' => $currentCategory?->parts ?? collect(),
        ]);
    }
}
