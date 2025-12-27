<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string|null $pattern
 * @property string $action
 * @property string $priority
 * @property string $applies_to
 * @property bool $is_active
 * @property int $trigger_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationRule active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationRule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationRule whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationRule whereAppliesTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationRule whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationRule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationRule wherePattern($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationRule wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationRule whereTriggerCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationRule whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationRule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ModerationRule extends Model
{
    protected $fillable = [
        'name', 'type', 'pattern', 'action', 'priority', 'applies_to', 'is_active', 'trigger_count'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function matches(string $content): bool
    {
        return match ($this->type) {
            'keyword' => stripos($content, $this->pattern) !== false,
            'regex' => preg_match($this->pattern, $content),
            default => false,
        };
    }
}
