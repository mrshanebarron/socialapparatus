<?php

namespace App\Livewire\Questions;

use App\Models\Question;
use Livewire\Component;

class Answer extends Component
{
    public Question $question;
    public string $answer = '';
    public bool $isPublic = true;

    public function mount(Question $question)
    {
        if ($question->recipient_id !== auth()->id()) {
            abort(403);
        }
        $this->question = $question;
        $this->answer = $question->answer ?? '';
        $this->isPublic = $question->is_public;
    }

    public function submit()
    {
        $this->validate([
            'answer' => 'required|string|max:2000',
        ]);

        $this->question->update([
            'answer' => $this->answer,
            'is_public' => $this->isPublic,
            'status' => 'answered',
            'answered_at' => now(),
        ]);

        return redirect()->route('questions.index');
    }

    public function decline()
    {
        $this->question->update(['status' => 'declined']);
        return redirect()->route('questions.index');
    }

    public function render()
    {
        return view('livewire.questions.answer')->layout('layouts.app');
    }
}
