<?php

namespace App\Livewire\Memories;

use App\Models\Memory;
use Livewire\Component;

class Index extends Component
{
    public $memories;

    public function mount()
    {
        $this->loadMemories();
    }

    public function loadMemories()
    {
        $this->memories = Memory::with(['memorable', 'user'])
            ->where('user_id', auth()->id())
            ->forToday()
            ->pending()
            ->orderByDesc('years_ago')
            ->get();
    }

    public function share($memoryId)
    {
        $memory = Memory::where('user_id', auth()->id())->findOrFail($memoryId);
        $memory->share();
        $this->loadMemories();
    }

    public function hide($memoryId)
    {
        $memory = Memory::where('user_id', auth()->id())->findOrFail($memoryId);
        $memory->hide();
        $this->loadMemories();
    }

    public function dismiss($memoryId)
    {
        $memory = Memory::where('user_id', auth()->id())->findOrFail($memoryId);
        $memory->dismiss();
        $this->loadMemories();
    }

    public function render()
    {
        return view('livewire.memories.index');
    }
}
