<?php

namespace App\Livewire\Boards;

use App\Models\Board;
use Livewire\Component;
use Illuminate\Support\Str;

class Create extends Component
{
    public string $name = '';
    public string $description = '';
    public string $visibility = 'private';
    public string $type = 'kanban';
    public ?string $coverImage = null;

    protected $rules = [
        'name' => 'required|min:2|max:100',
        'description' => 'nullable|max:500',
        'visibility' => 'required|in:public,private,team',
        'type' => 'required|in:kanban,gallery,list',
    ];

    public function create()
    {
        $this->validate();

        $board = Board::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'slug' => Str::slug($this->name) . '-' . Str::random(6),
            'description' => $this->description,
            'visibility' => $this->visibility,
            'type' => $this->type,
            'cover_image' => $this->coverImage,
        ]);

        // Create default columns for kanban type
        if ($this->type === 'kanban') {
            $defaultColumns = ['To Do', 'In Progress', 'Review', 'Done'];
            foreach ($defaultColumns as $index => $name) {
                $board->columns()->create([
                    'name' => $name,
                    'position' => $index,
                    'color' => ['#6366f1', '#f59e0b', '#3b82f6', '#10b981'][$index],
                ]);
            }
        }

        return redirect()->route('boards.show', $board);
    }

    public function render()
    {
        return view('livewire.boards.create')->layout('layouts.app');
    }
}
