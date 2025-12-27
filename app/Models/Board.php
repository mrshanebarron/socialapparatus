<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $boardable_type
 * @property int|null $boardable_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $cover_image
 * @property string $privacy
 * @property string $type
 * @property array<array-key, mixed>|null $settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent|null $boardable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $collaborators
 * @property-read int|null $collaborators_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BoardColumn> $columns
 * @property-read int|null $columns_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BoardItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereBoardableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereBoardableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board wherePrivacy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereUserId($value)
 * @mixin \Eloquent
 */
class Board extends Model
{
    protected $fillable = [
        'user_id', 'boardable_type', 'boardable_id', 'name', 'slug',
        'description', 'cover_image', 'privacy', 'type', 'settings'
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function boardable(): MorphTo
    {
        return $this->morphTo();
    }

    public function columns(): HasMany
    {
        return $this->hasMany(BoardColumn::class)->orderBy('position');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BoardItem::class);
    }

    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'board_collaborators')
            ->withPivot('role', 'invited_at', 'accepted_at')
            ->withTimestamps();
    }

    public function canEdit(User $user): bool
    {
        if ($this->user_id === $user->id) return true;

        return $this->collaborators()
            ->where('user_id', $user->id)
            ->whereIn('role', ['editor', 'admin'])
            ->exists();
    }
}
