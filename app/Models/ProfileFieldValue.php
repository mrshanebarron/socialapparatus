<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $profile_id
 * @property int $profile_field_id
 * @property string|null $value
 * @property string|null $visibility
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProfileField $field
 * @property-read string $effective_visibility
 * @property-read \App\Models\Profile $profile
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileFieldValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileFieldValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileFieldValue query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileFieldValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileFieldValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileFieldValue whereProfileFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileFieldValue whereProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileFieldValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileFieldValue whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileFieldValue whereVisibility($value)
 * @mixin \Eloquent
 */
class ProfileFieldValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'profile_field_id',
        'value',
        'visibility',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(ProfileField::class, 'profile_field_id');
    }

    public function getEffectiveVisibilityAttribute(): string
    {
        return $this->visibility ?? $this->field->visibility ?? 'public';
    }

    public function isVisibleTo(?User $viewer, Profile $profile): bool
    {
        $visibility = $this->effective_visibility;

        if ($visibility === 'public') {
            return true;
        }

        if (!$viewer) {
            return false;
        }

        if ($viewer->id === $profile->user_id) {
            return true;
        }

        if ($visibility === 'friends') {
            // TODO: Check if viewer is a friend
            return true; // Placeholder
        }

        return false;
    }
}
