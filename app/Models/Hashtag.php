<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $tag
 * @property-read int|null $posts_count
 * @property int $weekly_count
 * @property int $daily_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag trending(int $limit = 10)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag whereDailyCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag wherePostsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag whereWeeklyCount($value)
 * @mixin \Eloquent
 */
class Hashtag extends Model
{
    protected $fillable = [
        'tag',
        'posts_count',
        'weekly_count',
        'daily_count',
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }

    public static function findOrCreateByTag(string $tag): self
    {
        $cleanTag = strtolower(trim($tag, '#'));
        return self::firstOrCreate(['tag' => $cleanTag]);
    }

    public static function processHashtags(Post $post, string $content): void
    {
        // Remove old hashtags
        $post->hashtags()->detach();

        // Find all hashtags in content
        preg_match_all('/#(\w+)/u', $content, $matches);

        if (!empty($matches[1])) {
            $hashtags = array_unique($matches[1]);
            foreach ($hashtags as $tag) {
                $hashtag = self::findOrCreateByTag($tag);
                $post->hashtags()->attach($hashtag->id);
                $hashtag->increment('posts_count');
                $hashtag->increment('weekly_count');
                $hashtag->increment('daily_count');
            }
        }
    }

    public static function renderHashtags(string $content): string
    {
        return preg_replace_callback(
            '/#(\w+)/u',
            fn($matches) => '<a href="' . route('hashtag.show', strtolower($matches[1])) . '" class="text-indigo-600 dark:text-indigo-400 hover:underline">#' . $matches[1] . '</a>',
            $content
        );
    }

    public function scopeTrending($query, int $limit = 10)
    {
        return $query->orderByDesc('weekly_count')->limit($limit);
    }

    public static function resetDailyCounts(): void
    {
        self::query()->update(['daily_count' => 0]);
    }

    public static function resetWeeklyCounts(): void
    {
        self::query()->update(['weekly_count' => 0]);
    }
}
