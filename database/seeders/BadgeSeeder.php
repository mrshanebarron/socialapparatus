<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            // Engagement Badges
            [
                'name' => 'First Post',
                'slug' => 'first-post',
                'description' => 'Created your first post',
                'icon' => '📝',
                'color' => '#6366f1',
                'category' => 'engagement',
                'criteria' => ['type' => 'posts_count', 'value' => 1],
                'points' => 10,
            ],
            [
                'name' => 'Storyteller',
                'slug' => 'storyteller',
                'description' => 'Created 10 posts',
                'icon' => '📖',
                'color' => '#8b5cf6',
                'category' => 'engagement',
                'criteria' => ['type' => 'posts_count', 'value' => 10],
                'points' => 50,
            ],
            [
                'name' => 'Prolific Writer',
                'slug' => 'prolific-writer',
                'description' => 'Created 100 posts',
                'icon' => '✍️',
                'color' => '#a855f7',
                'category' => 'engagement',
                'criteria' => ['type' => 'posts_count', 'value' => 100],
                'points' => 200,
            ],

            // Social Badges
            [
                'name' => 'Friendly',
                'slug' => 'friendly',
                'description' => 'Made your first friend',
                'icon' => '🤝',
                'color' => '#22c55e',
                'category' => 'social',
                'criteria' => ['type' => 'friends_count', 'value' => 1],
                'points' => 10,
            ],
            [
                'name' => 'Social Butterfly',
                'slug' => 'social-butterfly',
                'description' => 'Made 50 friends',
                'icon' => '🦋',
                'color' => '#10b981',
                'category' => 'social',
                'criteria' => ['type' => 'friends_count', 'value' => 50],
                'points' => 100,
            ],
            [
                'name' => 'Popular',
                'slug' => 'popular',
                'description' => 'Reached 100 followers',
                'icon' => '⭐',
                'color' => '#f59e0b',
                'category' => 'social',
                'criteria' => ['type' => 'followers_count', 'value' => 100],
                'points' => 150,
            ],

            // Community Badges
            [
                'name' => 'Group Joiner',
                'slug' => 'group-joiner',
                'description' => 'Joined your first group',
                'icon' => '👥',
                'color' => '#3b82f6',
                'category' => 'community',
                'criteria' => ['type' => 'groups_joined', 'value' => 1],
                'points' => 10,
            ],
            [
                'name' => 'Community Leader',
                'slug' => 'community-leader',
                'description' => 'Created a group',
                'icon' => '👑',
                'color' => '#eab308',
                'category' => 'community',
                'criteria' => ['type' => 'groups_created', 'value' => 1],
                'points' => 50,
            ],

            // Event Badges
            [
                'name' => 'Event Goer',
                'slug' => 'event-goer',
                'description' => 'RSVP\'d to your first event',
                'icon' => '📅',
                'color' => '#ec4899',
                'category' => 'events',
                'criteria' => ['type' => 'events_attended', 'value' => 1],
                'points' => 10,
            ],
            [
                'name' => 'Event Host',
                'slug' => 'event-host',
                'description' => 'Created an event',
                'icon' => '🎉',
                'color' => '#f43f5e',
                'category' => 'events',
                'criteria' => ['type' => 'events_created', 'value' => 1],
                'points' => 30,
            ],

            // Special Badges
            [
                'name' => 'Early Adopter',
                'slug' => 'early-adopter',
                'description' => 'Joined during the early days',
                'icon' => '🚀',
                'color' => '#6366f1',
                'category' => 'special',
                'criteria' => ['type' => 'manual'],
                'points' => 100,
            ],
            [
                'name' => 'Verified',
                'slug' => 'verified',
                'description' => 'Verified member',
                'icon' => '✓',
                'color' => '#0ea5e9',
                'category' => 'special',
                'criteria' => ['type' => 'manual'],
                'points' => 0,
            ],
            [
                'name' => 'Birthday',
                'slug' => 'birthday',
                'description' => 'It\'s your special day!',
                'icon' => '🎂',
                'color' => '#f472b6',
                'category' => 'special',
                'criteria' => ['type' => 'birthday'],
                'points' => 0,
            ],

            // Milestone Badges
            [
                'name' => 'One Year',
                'slug' => 'one-year',
                'description' => 'Member for 1 year',
                'icon' => '🎖️',
                'color' => '#78716c',
                'category' => 'milestone',
                'criteria' => ['type' => 'account_age_days', 'value' => 365],
                'points' => 100,
            ],
            [
                'name' => 'Five Years',
                'slug' => 'five-years',
                'description' => 'Member for 5 years',
                'icon' => '🏆',
                'color' => '#fbbf24',
                'category' => 'milestone',
                'criteria' => ['type' => 'account_age_days', 'value' => 1825],
                'points' => 500,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['slug' => $badge['slug']],
                $badge
            );
        }
    }
}
