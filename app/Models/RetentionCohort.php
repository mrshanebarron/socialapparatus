<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon $cohort_date
 * @property string $cohort_type
 * @property int $period
 * @property int $cohort_size
 * @property int $retained_users
 * @property numeric $retention_rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder<static>|RetentionCohort monthly()
 * @method static Builder<static>|RetentionCohort newModelQuery()
 * @method static Builder<static>|RetentionCohort newQuery()
 * @method static Builder<static>|RetentionCohort query()
 * @method static Builder<static>|RetentionCohort weekly()
 * @method static Builder<static>|RetentionCohort whereCohortDate($value)
 * @method static Builder<static>|RetentionCohort whereCohortSize($value)
 * @method static Builder<static>|RetentionCohort whereCohortType($value)
 * @method static Builder<static>|RetentionCohort whereCreatedAt($value)
 * @method static Builder<static>|RetentionCohort whereId($value)
 * @method static Builder<static>|RetentionCohort wherePeriod($value)
 * @method static Builder<static>|RetentionCohort whereRetainedUsers($value)
 * @method static Builder<static>|RetentionCohort whereRetentionRate($value)
 * @method static Builder<static>|RetentionCohort whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RetentionCohort extends Model
{
    use HasFactory;

    protected $fillable = ['cohort_date', 'cohort_type', 'period', 'cohort_size', 'retained_users', 'retention_rate'];

    protected $casts = ['cohort_date' => 'date', 'retention_rate' => 'decimal:2'];

    public function scopeWeekly(Builder $query): Builder
    {
        return $query->where('cohort_type', 'weekly');
    }

    public function scopeMonthly(Builder $query): Builder
    {
        return $query->where('cohort_type', 'monthly');
    }
}
