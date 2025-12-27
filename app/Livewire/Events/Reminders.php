<?php

namespace App\Livewire\Events;

use App\Models\Event;
use App\Models\EventReminder;
use Livewire\Component;

class Reminders extends Component
{
    public Event $event;
    public bool $showModal = false;
    public string $remindBefore = '1_day';
    public string $notificationType = 'notification';

    protected $rules = [
        'remindBefore' => 'required|in:15_min,30_min,1_hour,2_hours,1_day,1_week',
        'notificationType' => 'required|in:notification,email,both',
    ];

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function addReminder()
    {
        $this->validate();

        $remindAt = match ($this->remindBefore) {
            '15_min' => $this->event->start_date->subMinutes(15),
            '30_min' => $this->event->start_date->subMinutes(30),
            '1_hour' => $this->event->start_date->subHour(),
            '2_hours' => $this->event->start_date->subHours(2),
            '1_day' => $this->event->start_date->subDay(),
            '1_week' => $this->event->start_date->subWeek(),
        };

        auth()->user()->eventReminders()->create([
            'event_id' => $this->event->id,
            'remind_before' => $this->remindBefore,
            'remind_at' => $remindAt,
            'notification_type' => $this->notificationType,
        ]);

        $this->showModal = false;
        $this->reset(['remindBefore', 'notificationType']);
    }

    public function removeReminder($reminderId)
    {
        auth()->user()->eventReminders()->where('id', $reminderId)->delete();
    }

    public function render()
    {
        $reminders = auth()->user()->eventReminders()
            ->where('event_id', $this->event->id)
            ->orderBy('remind_at')
            ->get();

        return view('livewire.events.reminders', [
            'reminders' => $reminders,
        ]);
    }
}
