<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property string|null $file_path
 * @property \Illuminate\Support\Carbon $requested_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataExport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataExport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataExport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataExport whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataExport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataExport whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataExport whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataExport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataExport whereRequestedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataExport whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataExport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataExport whereUserId($value)
 * @mixin \Eloquent
 */
class DataExport extends Model
{
    protected $fillable = [
        'user_id', 'status', 'file_path',
        'requested_at', 'completed_at', 'expires_at'
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'completed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
