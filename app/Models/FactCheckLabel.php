<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $color
 * @property string|null $icon
 * @property string $description
 * @property int $severity
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContentVerification> $verifications
 * @property-read int|null $verifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckLabel active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckLabel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckLabel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckLabel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckLabel whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckLabel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckLabel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckLabel whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckLabel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckLabel whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckLabel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckLabel whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckLabel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckLabel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FactCheckLabel extends Model
{
    protected $fillable = ['name', 'slug', 'color', 'icon', 'description', 'severity', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function verifications(): HasMany
    {
        return $this->hasMany(ContentVerification::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
