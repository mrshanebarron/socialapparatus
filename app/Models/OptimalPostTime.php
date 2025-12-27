<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $day_of_week
 * @property string $optimal_time
 * @property numeric $engagement_score
 * @property int $sample_size
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $day_name
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OptimalPostTime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OptimalPostTime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OptimalPostTime query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OptimalPostTime whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OptimalPostTime whereDayOfWeek($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OptimalPostTime whereEngagementScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OptimalPostTime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OptimalPostTime whereOptimalTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OptimalPostTime whereSampleSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OptimalPostTime whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OptimalPostTime whereUserId($value)
 * @mixin \Eloquent
 */
class OptimalPostTime extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'day_of_week', 'optimal_time', 'engagement_score', 'sample_size'];

    protected $casts = ['engagement_score' => 'decimal:2'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDayNameAttribute(): string
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        return $days[$this->day_of_week] ?? 'Unknown';
    }
}
