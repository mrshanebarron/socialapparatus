<?php

namespace App\Livewire\Admin;

use App\Models\PlatformMetric;
use App\Models\AdminInsight;
use App\Models\User;
use App\Models\Post;
use App\Models\Group;
use Livewire\Component;

class Analytics extends Component
{
    public $period = '7';
    public $metrics = [];
    public $insights = [];

    public function mount()
    {
        $this->loadMetrics();
        $this->loadInsights();
    }

    public function setPeriod($days)
    {
        $this->period = $days;
        $this->loadMetrics();
    }

    public function loadMetrics()
    {
        $days = (int) $this->period;

        $this->metrics = [
            'total_users' => User::count(),
            'new_users' => User::where('created_at', '>=', now()->subDays($days))->count(),
            'active_users' => User::where('last_activity_at', '>=', now()->subDays($days))->count(),
            'total_posts' => Post::count(),
            'new_posts' => Post::where('created_at', '>=', now()->subDays($days))->count(),
            'total_groups' => Group::count(),
            'new_groups' => Group::where('created_at', '>=', now()->subDays($days))->count(),
        ];

        // Get daily metrics for chart
        $this->metrics['daily'] = PlatformMetric::where('date', '>=', now()->subDays($days))
            ->orderBy('date')
            ->get()
            ->map(fn($m) => [
                'date' => $m->date->format('M j'),
                'users' => $m->new_users,
                'posts' => $m->total_posts,
                'active' => $m->active_users,
            ])
            ->toArray();
    }

    public function loadInsights()
    {
        $this->insights = AdminInsight::new()
            ->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')")
            ->take(5)
            ->get();
    }

    public function dismissInsight($id)
    {
        AdminInsight::find($id)?->update(['status' => 'dismissed']);
        $this->loadInsights();
    }

    public function render()
    {
        return view('livewire.admin.analytics');
    }
}
