<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $type
 * @property string $title
 * @property string $description
 * @property array<array-key, mixed>|null $data
 * @property string $priority
 * @property string $status
 * @property int|null $viewed_by
 * @property \Illuminate\Support\Carbon|null $viewed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $viewer
 * @method static Builder<static>|AdminInsight critical()
 * @method static Builder<static>|AdminInsight new()
 * @method static Builder<static>|AdminInsight newModelQuery()
 * @method static Builder<static>|AdminInsight newQuery()
 * @method static Builder<static>|AdminInsight query()
 * @method static Builder<static>|AdminInsight whereCreatedAt($value)
 * @method static Builder<static>|AdminInsight whereData($value)
 * @method static Builder<static>|AdminInsight whereDescription($value)
 * @method static Builder<static>|AdminInsight whereId($value)
 * @method static Builder<static>|AdminInsight wherePriority($value)
 * @method static Builder<static>|AdminInsight whereStatus($value)
 * @method static Builder<static>|AdminInsight whereTitle($value)
 * @method static Builder<static>|AdminInsight whereType($value)
 * @method static Builder<static>|AdminInsight whereUpdatedAt($value)
 * @method static Builder<static>|AdminInsight whereViewedAt($value)
 * @method static Builder<static>|AdminInsight whereViewedBy($value)
 * @mixin \Eloquent
 */
class AdminInsight extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'title', 'description', 'data', 'priority', 'status', 'viewed_by', 'viewed_at'];

    protected $casts = ['data' => 'array', 'viewed_at' => 'datetime'];

    public function viewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'viewed_by');
    }

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    public function scopeCritical(Builder $query): Builder
    {
        return $query->where('priority', 'critical');
    }
}
