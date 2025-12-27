<?php

namespace App\Livewire\ProfileFrames;

use App\Models\ProfileFrame;
use App\Models\ProfileFrameCategory;
use App\Models\UserProfileFrame;
use App\Models\UserOwnedFrame;
use Livewire\Component;

class Index extends Component
{
    public $categories;
    public $selectedCategory = null;
    public $currentFrame = null;

    public function mount()
    {
        $this->categories = ProfileFrameCategory::active()->with(['frames' => fn($q) => $q->active()])->orderBy('sort_order')->get();
        $this->currentFrame = UserProfileFrame::where('user_id', auth()->id())->where('is_active', true)->with('frame')->first();
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
    }

    public function applyFrame($frameId)
    {
        $frame = ProfileFrame::findOrFail($frameId);

        if ($frame->coin_cost > 0 && !$this->ownsFrame($frame)) {
            session()->flash('error', 'You need to purchase this frame first.');
            return;
        }

        UserProfileFrame::where('user_id', auth()->id())->update(['is_active' => false]);

        UserProfileFrame::create([
            'user_id' => auth()->id(),
            'profile_frame_id' => $frameId,
            'is_active' => true,
            'applied_at' => now(),
        ]);

        $frame->increment('times_used');
        $this->currentFrame = UserProfileFrame::where('user_id', auth()->id())->where('is_active', true)->with('frame')->first();
    }

    public function removeFrame()
    {
        UserProfileFrame::where('user_id', auth()->id())->update(['is_active' => false]);
        $this->currentFrame = null;
    }

    public function purchaseFrame($frameId)
    {
        $frame = ProfileFrame::findOrFail($frameId);
        $user = auth()->user();

        if ($user->coinBalance && $user->coinBalance->balance >= $frame->coin_cost) {
            $user->coinBalance->decrement('balance', $frame->coin_cost);
            UserOwnedFrame::create([
                'user_id' => $user->id,
                'profile_frame_id' => $frameId,
                'purchased_at' => now(),
                'coins_spent' => $frame->coin_cost,
            ]);
            session()->flash('success', 'Frame purchased successfully!');
        } else {
            session()->flash('error', 'Insufficient coins.');
        }
    }

    protected function ownsFrame(ProfileFrame $frame): bool
    {
        if ($frame->coin_cost === 0) return true;
        return UserOwnedFrame::where('user_id', auth()->id())->where('profile_frame_id', $frame->id)->exists();
    }

    public function render()
    {
        return view('livewire.profile-frames.index');
    }
}
