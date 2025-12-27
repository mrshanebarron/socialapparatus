<?php

namespace App\Livewire\Stories;

use App\Models\Story;
use App\Models\StoryPoll;
use App\Models\StoryQuestion;
use App\Models\StorySlider;
use App\Models\StoryQuiz;
use Livewire\Component;

class StoryInteractions extends Component
{
    public Story $story;
    public string $interactionType = 'poll';
    public bool $showAddModal = false;

    // Poll fields
    public string $pollQuestion = '';
    public array $pollOptions = ['', ''];
    public bool $allowMultiple = false;
    public ?int $pollDuration = 24;

    // Question fields
    public string $questionText = '';
    public string $placeholderText = 'Ask me anything...';

    // Slider fields
    public string $sliderQuestion = '';
    public string $sliderEmoji = '😍';

    // Quiz fields
    public string $quizQuestion = '';
    public array $quizOptions = ['', '', '', ''];
    public int $correctOption = 0;

    // User responses
    public string $questionResponse = '';
    public ?int $sliderValue = 50;

    public function mount(Story $story)
    {
        $this->story = $story;
    }

    public function openAddModal($type = 'poll')
    {
        $this->interactionType = $type;
        $this->showAddModal = true;
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->resetFields();
    }

    protected function resetFields()
    {
        $this->pollQuestion = '';
        $this->pollOptions = ['', ''];
        $this->allowMultiple = false;
        $this->pollDuration = 24;
        $this->questionText = '';
        $this->placeholderText = 'Ask me anything...';
        $this->sliderQuestion = '';
        $this->sliderEmoji = '😍';
        $this->quizQuestion = '';
        $this->quizOptions = ['', '', '', ''];
        $this->correctOption = 0;
    }

    public function addPollOption()
    {
        if (count($this->pollOptions) < 4) {
            $this->pollOptions[] = '';
        }
    }

    public function removePollOption($index)
    {
        if (count($this->pollOptions) > 2) {
            unset($this->pollOptions[$index]);
            $this->pollOptions = array_values($this->pollOptions);
        }
    }

    public function createPoll()
    {
        $this->validate([
            'pollQuestion' => 'required|min:3|max:255',
            'pollOptions' => 'required|array|min:2|max:4',
            'pollOptions.*' => 'required|min:1|max:100',
        ]);

        StoryPoll::create([
            'story_id' => $this->story->id,
            'question' => $this->pollQuestion,
            'options' => array_filter($this->pollOptions),
            'allow_multiple' => $this->allowMultiple,
            'ends_at' => $this->pollDuration ? now()->addHours($this->pollDuration) : null,
        ]);

        $this->closeAddModal();
        $this->dispatch('refreshStory');
    }

    public function createQuestion()
    {
        $this->validate([
            'questionText' => 'required|min:3|max:255',
        ]);

        StoryQuestion::create([
            'story_id' => $this->story->id,
            'question' => $this->questionText,
            'placeholder' => $this->placeholderText,
        ]);

        $this->closeAddModal();
        $this->dispatch('refreshStory');
    }

    public function createSlider()
    {
        $this->validate([
            'sliderQuestion' => 'required|min:3|max:255',
            'sliderEmoji' => 'required|max:10',
        ]);

        StorySlider::create([
            'story_id' => $this->story->id,
            'question' => $this->sliderQuestion,
            'emoji' => $this->sliderEmoji,
        ]);

        $this->closeAddModal();
        $this->dispatch('refreshStory');
    }

    public function createQuiz()
    {
        $this->validate([
            'quizQuestion' => 'required|min:3|max:255',
            'quizOptions' => 'required|array|size:4',
            'quizOptions.*' => 'required|min:1|max:100',
            'correctOption' => 'required|integer|min:0|max:3',
        ]);

        StoryQuiz::create([
            'story_id' => $this->story->id,
            'question' => $this->quizQuestion,
            'options' => $this->quizOptions,
            'correct_option' => $this->correctOption,
        ]);

        $this->closeAddModal();
        $this->dispatch('refreshStory');
    }

    public function votePoll($pollId, $optionIndex)
    {
        $poll = StoryPoll::findOrFail($pollId);

        if ($poll->hasVoted(auth()->user())) {
            return;
        }

        $poll->votes()->create([
            'user_id' => auth()->id(),
            'option_index' => $optionIndex,
        ]);

        $poll->increment('total_votes');
    }

    public function submitQuestionResponse($questionId)
    {
        if (empty(trim($this->questionResponse))) return;

        $question = StoryQuestion::findOrFail($questionId);

        $question->responses()->create([
            'user_id' => auth()->id(),
            'response' => $this->questionResponse,
        ]);

        $this->questionResponse = '';
    }

    public function submitSliderResponse($sliderId)
    {
        $slider = StorySlider::findOrFail($sliderId);

        $existing = $slider->responses()->where('user_id', auth()->id())->first();

        if ($existing) {
            $existing->update(['value' => $this->sliderValue]);
        } else {
            $slider->responses()->create([
                'user_id' => auth()->id(),
                'value' => $this->sliderValue,
            ]);
            $slider->increment('total_responses');
        }
    }

    public function answerQuiz($quizId, $optionIndex)
    {
        $quiz = StoryQuiz::findOrFail($quizId);

        if ($quiz->hasAnswered(auth()->user())) {
            return;
        }

        $isCorrect = $optionIndex === $quiz->correct_option;

        $quiz->answers()->create([
            'user_id' => auth()->id(),
            'selected_option' => $optionIndex,
            'is_correct' => $isCorrect,
        ]);

        if ($isCorrect) {
            $quiz->increment('correct_count');
        }
    }

    public function render()
    {
        $polls = $this->story->polls()->with('votes')->get();
        $questions = $this->story->questions()->with('responses.user')->get();
        $sliders = $this->story->sliders()->with('responses')->get();
        $quizzes = $this->story->quizzes()->with('answers')->get();

        return view('livewire.stories.story-interactions', [
            'polls' => $polls,
            'questions' => $questions,
            'sliders' => $sliders,
            'quizzes' => $quizzes,
        ]);
    }
}
