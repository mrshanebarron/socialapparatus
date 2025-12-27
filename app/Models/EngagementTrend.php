<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon $date
 * @property string $metric_type
 * @property string|null $segment
 * @property string|null $segment_value
 * @property int $count
 * @property numeric $change_percent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngagementTrend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngagementTrend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngagementTrend query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngagementTrend whereChangePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngagementTrend whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngagementTrend whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngagementTrend whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngagementTrend whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngagementTrend whereMetricType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngagementTrend whereSegment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngagementTrend whereSegmentValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngagementTrend whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EngagementTrend extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'metric_type', 'segment', 'segment_value', 'count', 'change_percent'];

    protected $casts = ['date' => 'date', 'change_percent' => 'decimal:2'];

    public function isGrowing(): bool
    {
        return $this->change_percent > 0;
    }
}
