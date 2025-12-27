<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $group_id
 * @property int|null $page_id
 * @property string|null $content
 * @property array<array-key, mixed>|null $media
 * @property string $privacy
 * @property array<array-key, mixed>|null $privacy_settings
 * @property \Illuminate\Support\Carbon $last_edited_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Group|null $group
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostDraft newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostDraft newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostDraft query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostDraft whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostDraft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostDraft whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostDraft whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostDraft whereLastEditedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostDraft whereMedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostDraft wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostDraft wherePrivacy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostDraft wherePrivacySettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostDraft whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostDraft whereUserId($value)
 * @mixin \Eloquent
 */
class PostDraft extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'group_id', 'page_id', 'content', 'media', 'privacy', 'privacy_settings', 'last_edited_at'];

    protected $casts = ['media' => 'array', 'privacy_settings' => 'array', 'last_edited_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
