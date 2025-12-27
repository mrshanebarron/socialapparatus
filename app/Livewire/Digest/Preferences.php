<?php

namespace App\Livewire\Digest;

use App\Models\DigestPreference;
use Livewire\Component;

class Preferences extends Component
{
    public bool $digestEnabled = true;
    public string $frequency = 'weekly';
    public string $preferredDay = 'monday';
    public string $preferredTime = '09:00';
    public array $includedContent = ['posts', 'events', 'groups', 'friends'];
    public bool $includeRecommendations = true;
    public bool $includeAnalytics = false;

    public function mount()
    {
        $preference = DigestPreference::where('user_id', auth()->id())->first();

        if ($preference) {
            $this->digestEnabled = $preference->is_enabled;
            $this->frequency = $preference->frequency;
            $this->preferredDay = $preference->preferred_day;
            $this->preferredTime = $preference->preferred_time ?? '09:00';
            $this->includedContent = $preference->included_content ?? ['posts', 'events', 'groups', 'friends'];
            $this->includeRecommendations = $preference->include_recommendations ?? true;
            $this->includeAnalytics = $preference->include_analytics ?? false;
        }
    }

    public function save()
    {
        DigestPreference::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'is_enabled' => $this->digestEnabled,
                'frequency' => $this->frequency,
                'preferred_day' => $this->preferredDay,
                'preferred_time' => $this->preferredTime,
                'included_content' => $this->includedContent,
                'include_recommendations' => $this->includeRecommendations,
                'include_analytics' => $this->includeAnalytics,
            ]
        );

        session()->flash('message', 'Digest preferences saved!');
    }

    public function toggleContentType($type)
    {
        if (in_array($type, $this->includedContent)) {
            $this->includedContent = array_diff($this->includedContent, [$type]);
        } else {
            $this->includedContent[] = $type;
        }
        $this->includedContent = array_values($this->includedContent);
    }

    public function render()
    {
        return view('livewire.digest.preferences')->layout('layouts.app');
    }
}
