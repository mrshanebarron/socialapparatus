<?php

namespace App\Livewire\Questions;

use App\Models\User;
use Livewire\Component;

class Ask extends Component
{
    public User $recipient;
    public string $question = '';
    public bool $isAnonymous = false;
    public bool $submitted = false;

    public function mount(User $recipient)
    {
        $this->recipient = $recipient;

        $profile = $recipient->profile;
        if (!$profile?->allow_questions) {
            abort(403, 'This user is not accepting questions.');
        }
    }

    public function submit()
    {
        $profile = $this->recipient->profile;

        if ($this->isAnonymous && !$profile?->allow_anonymous_questions) {
            session()->flash('error', 'This user does not accept anonymous questions.');
            return;
        }

        $this->validate([
            'question' => 'required|string|max:500',
        ]);

        auth()->user()->askQuestion($this->recipient, $this->question, $this->isAnonymous);
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.questions.ask', [
            'allowAnonymous' => $this->recipient->profile?->allow_anonymous_questions ?? false,
        ]);
    }
}
