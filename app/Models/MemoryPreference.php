<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property bool $enabled
 * @property string $notification_time
 * @property array<array-key, mixed>|null $excluded_years
 * @property array<array-key, mixed>|null $excluded_people
 * @property bool $include_posts
 * @property bool $include_photos
 * @property bool $include_friendships
 * @property bool $include_events
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryPreference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryPreference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryPreference query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryPreference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryPreference whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryPreference whereExcludedPeople($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryPreference whereExcludedYears($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryPreference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryPreference whereIncludeEvents($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryPreference whereIncludeFriendships($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryPreference whereIncludePhotos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryPreference whereIncludePosts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryPreference whereNotificationTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryPreference whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryPreference whereUserId($value)
 * @mixin \Eloquent
 */
class MemoryPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'enabled',
        'notification_time',
        'excluded_years',
        'excluded_people',
        'include_posts',
        'include_photos',
        'include_friendships',
        'include_events',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'excluded_years' => 'array',
        'excluded_people' => 'array',
        'include_posts' => 'boolean',
        'include_photos' => 'boolean',
        'include_friendships' => 'boolean',
        'include_events' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
