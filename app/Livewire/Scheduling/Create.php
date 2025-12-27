<?php

namespace App\Livewire\Scheduling;

use App\Models\ScheduledPost;
use App\Models\OptimalPostTime;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $content = '';
    public $media = [];
    public $privacy = 'public';
    public $scheduled_date = '';
    public $scheduled_time = '';
    public $timezone;
    public $suggestedTimes = [];

    protected $rules = [
        'content' => 'required|min:1',
        'scheduled_date' => 'required|date|after_or_equal:today',
        'scheduled_time' => 'required',
        'privacy' => 'required|in:public,friends,private',
    ];

    public function mount()
    {
        $this->timezone = config('app.timezone');
        $this->loadSuggestedTimes();
    }

    public function loadSuggestedTimes()
    {
        $this->suggestedTimes = OptimalPostTime::where('user_id', auth()->id())
            ->orderByDesc('engagement_score')
            ->take(3)
            ->get()
            ->map(fn($t) => [
                'day' => $t->day_name,
                'time' => $t->optimal_time,
                'score' => $t->engagement_score,
            ])
            ->toArray();
    }

    public function schedule()
    {
        $this->validate();

        $scheduledFor = \Carbon\Carbon::parse("{$this->scheduled_date} {$this->scheduled_time}", $this->timezone);

        $mediaPaths = [];
        foreach ($this->media as $file) {
            $mediaPaths[] = $file->store('scheduled-posts', 'public');
        }

        ScheduledPost::create([
            'user_id' => auth()->id(),
            'content' => $this->content,
            'media' => $mediaPaths ?: null,
            'privacy' => $this->privacy,
            'scheduled_for' => $scheduledFor,
            'timezone' => $this->timezone,
            'status' => 'scheduled',
        ]);

        session()->flash('success', 'Post scheduled successfully!');
        return redirect()->route('scheduling.index');
    }

    public function useSuggestedTime($index)
    {
        if (isset($this->suggestedTimes[$index])) {
            $suggestion = $this->suggestedTimes[$index];
            $this->scheduled_time = $suggestion['time'];
        }
    }

    public function render()
    {
        return view('livewire.scheduling.create');
    }
}
