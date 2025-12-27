<?php

namespace App\Livewire\Feed;

use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BirthdayReminders extends Component
{
    public string $birthdayWish = '';
    public ?int $wishingUserId = null;

    public function sendWish($userId)
    {
        if (!auth()->check() || !trim($this->birthdayWish)) {
            return;
        }

        // Create a post on the birthday person's wall
        $user = \App\Models\User::find($userId);
        if (!$user) {
            return;
        }

        \App\Models\Post::create([
            'user_id' => auth()->id(),
            'body' => $this->birthdayWish,
            'type' => 'text',
            'visibility' => 'public',
        ]);

        // Create notification for the birthday person
        $user->notifications()->create([
            'type' => 'birthday_wish',
            'data' => [
                'message' => auth()->user()->name . ' wished you a happy birthday!',
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'user_avatar' => auth()->user()->profile_photo_url,
            ],
        ]);

        $this->birthdayWish = '';
        $this->wishingUserId = null;

        session()->flash('message', 'Birthday wish sent!');
    }

    public function render()
    {
        $today = now();

        // Get friends with birthdays today
        $friendIds = auth()->user()->friends()->pluck('id');

        $todayBirthdays = Profile::whereIn('user_id', $friendIds)
            ->whereMonth('birthday', $today->month)
            ->whereDay('birthday', $today->day)
            ->with('user')
            ->get();

        // Get upcoming birthdays (next 7 days)
        $upcomingBirthdays = Profile::whereIn('user_id', $friendIds)
            ->whereNotNull('birthday')
            ->where(function ($query) use ($today) {
                // This week excluding today
                for ($i = 1; $i <= 7; $i++) {
                    $date = $today->copy()->addDays($i);
                    $query->orWhere(function ($q) use ($date) {
                        $q->whereMonth('birthday', $date->month)
                          ->whereDay('birthday', $date->day);
                    });
                }
            })
            ->with('user')
            ->get()
            ->sortBy(function ($profile) use ($today) {
                $birthday = $profile->birthday->copy()->year($today->year);
                if ($birthday->lt($today)) {
                    $birthday->addYear();
                }
                return $birthday->diffInDays($today);
            });

        return view('livewire.feed.birthday-reminders', [
            'todayBirthdays' => $todayBirthdays,
            'upcomingBirthdays' => $upcomingBirthdays,
        ]);
    }
}
