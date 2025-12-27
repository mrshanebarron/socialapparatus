<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $board_id
 * @property int|null $board_column_id
 * @property int $user_id
 * @property string $title
 * @property string|null $description
 * @property string $type
 * @property string|null $url
 * @property string|null $file_path
 * @property array<array-key, mixed>|null $metadata
 * @property int $position
 * @property bool $is_completed
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property string|null $priority
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $assignees
 * @property-read int|null $assignees_count
 * @property-read \App\Models\Board $board
 * @property-read \App\Models\BoardColumn|null $column
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BoardItemComment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem whereBoardColumnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem whereIsCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItem whereUserId($value)
 * @mixin \Eloquent
 */
class BoardItem extends Model
{
    protected $fillable = [
        'board_id', 'board_column_id', 'user_id', 'title', 'description',
        'type', 'url', 'file_path', 'metadata', 'position', 'is_completed',
        'due_date', 'priority'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_completed' => 'boolean',
        'due_date' => 'datetime',
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function column(): BelongsTo
    {
        return $this->belongsTo(BoardColumn::class, 'board_column_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'board_item_assignees')
            ->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BoardItemComment::class);
    }
}
