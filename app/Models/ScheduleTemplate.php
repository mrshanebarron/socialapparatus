<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $description
 * @property array<array-key, mixed> $schedule
 * @property string $timezone
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleTemplate whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleTemplate whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleTemplate whereSchedule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleTemplate whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleTemplate whereUserId($value)
 * @mixin \Eloquent
 */
class ScheduleTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'description', 'schedule', 'timezone', 'is_active'];

    protected $casts = ['schedule' => 'array', 'is_active' => 'boolean'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
