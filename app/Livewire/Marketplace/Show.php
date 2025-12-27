<?php

namespace App\Livewire\Marketplace;

use App\Models\MarketplaceListing;
use Livewire\Component;

class Show extends Component
{
    public MarketplaceListing $listing;
    public int $currentImageIndex = 0;
    public bool $showMessageModal = false;
    public string $message = '';

    public function mount(MarketplaceListing $listing)
    {
        $this->listing = $listing;
        $this->listing->increment('views_count');
    }

    public function nextImage()
    {
        if ($this->listing->images && count($this->listing->images) > 0) {
            $this->currentImageIndex = ($this->currentImageIndex + 1) % count($this->listing->images);
        }
    }

    public function previousImage()
    {
        if ($this->listing->images && count($this->listing->images) > 0) {
            $this->currentImageIndex = ($this->currentImageIndex - 1 + count($this->listing->images)) % count($this->listing->images);
        }
    }

    public function setImage(int $index)
    {
        $this->currentImageIndex = $index;
    }

    public function sendMessage()
    {
        if (!auth()->check() || !trim($this->message)) return;

        // Create or find conversation with seller
        $conversation = auth()->user()->conversations()
            ->whereHas('participants', function ($q) {
                $q->where('user_id', $this->listing->user_id);
            })
            ->whereDoesntHave('participants', function ($q) {
                $q->whereNotIn('user_id', [auth()->id(), $this->listing->user_id]);
            })
            ->first();

        if (!$conversation) {
            $conversation = \App\Models\Conversation::create(['type' => 'direct']);
            $conversation->participants()->createMany([
                ['user_id' => auth()->id()],
                ['user_id' => $this->listing->user_id],
            ]);
        }

        $conversation->messages()->create([
            'user_id' => auth()->id(),
            'body' => "Regarding: {$this->listing->title}\n\n{$this->message}",
        ]);

        $this->listing->increment('messages_count');
        $this->showMessageModal = false;
        $this->message = '';

        session()->flash('success', 'Message sent to seller!');
    }

    public function markAsSold()
    {
        if ($this->listing->user_id !== auth()->id()) return;

        $this->listing->update(['status' => 'sold']);
    }

    public function deleteListing()
    {
        if ($this->listing->user_id !== auth()->id()) return;

        $this->listing->delete();
        return $this->redirect(route('marketplace.index'), navigate: true);
    }

    public function render()
    {
        $relatedListings = MarketplaceListing::where('category_id', $this->listing->category_id)
            ->where('id', '!=', $this->listing->id)
            ->active()
            ->limit(4)
            ->get();

        return view('livewire.marketplace.show', [
            'relatedListings' => $relatedListings,
        ]);
    }
}
