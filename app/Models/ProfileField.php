<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $label
 * @property string|null $description
 * @property string $type
 * @property array<array-key, mixed>|null $options
 * @property string|null $placeholder
 * @property string|null $default_value
 * @property bool $is_required
 * @property bool $is_visible_in_registration
 * @property bool $is_visible_in_profile
 * @property bool $is_searchable
 * @property string $visibility
 * @property int $sort_order
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read array $options_array
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProfileFieldValue> $values
 * @property-read int|null $values_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField visibleInProfile()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField visibleInRegistration()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField whereDefaultValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField whereIsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField whereIsSearchable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField whereIsVisibleInProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField whereIsVisibleInRegistration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField wherePlaceholder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileField whereVisibility($value)
 * @mixin \Eloquent
 */
class ProfileField extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'description',
        'type',
        'options',
        'placeholder',
        'default_value',
        'is_required',
        'is_visible_in_registration',
        'is_visible_in_profile',
        'is_searchable',
        'visibility',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_visible_in_registration' => 'boolean',
        'is_visible_in_profile' => 'boolean',
        'is_searchable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(ProfileFieldValue::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVisibleInRegistration($query)
    {
        return $query->where('is_visible_in_registration', true);
    }

    public function scopeVisibleInProfile($query)
    {
        return $query->where('is_visible_in_profile', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function getOptionsArrayAttribute(): array
    {
        return $this->options ?? [];
    }
}
