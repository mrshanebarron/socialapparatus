<?php

namespace App\Livewire\Questions;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ProfileQuestions extends Component
{
    use WithPagination;

    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function toggleLike($questionId)
    {
        $question = $this->user->receivedQuestions()
            ->where('status', 'answered')
            ->where('is_public', true)
            ->find($questionId);

        if (!$question) return;

        $liked = $question->likes()->where('user_id', auth()->id())->exists();

        if ($liked) {
            $question->likes()->detach(auth()->id());
            $question->decrement('likes_count');
        } else {
            $question->likes()->attach(auth()->id());
            $question->increment('likes_count');
        }
    }

    public function render()
    {
        $questions = $this->user->receivedQuestions()
            ->where('status', 'answered')
            ->where('is_public', true)
            ->with(['asker.profile'])
            ->orderByDesc('answered_at')
            ->paginate(10);

        return view('livewire.questions.profile-questions', [
            'questions' => $questions,
        ]);
    }
}
